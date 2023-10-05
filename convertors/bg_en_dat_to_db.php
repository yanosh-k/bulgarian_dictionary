<?php

    // Read options
	$cliOptions = getopt('h:u:p:d:');
	$servername = $cliOptions['h'];
	$username = $cliOptions['u'];
	$password = $cliOptions['p'];
	$database = $cliOptions['d'];

    
    // Establish a DB connection
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		echo "# Connected to database successfully\n";
	} catch(PDOException $e) {
		echo "# Connection to database failed: " . $e->getMessage() . "\n";
	}

    // Read translation content directly from sourceforge (requires extension=openssl in php.ini)
    $n = 1;
    $content = "";
    echo "# Downloading dictionary from https://sourceforge.net/p/bgoffice/code/HEAD/tree/trunk/dictionaries/data/bg-en/";
    while ($n < 33) {
        $num = str_pad($n,2,"0",STR_PAD_LEFT);
        $url = "https://sourceforge.net/p/bgoffice/code/HEAD/tree/trunk/dictionaries/data/bg-en/d$num.txt?format=raw";

        echo "\r# Downloading dictionary $num/32 from https://sourceforge.net/p/bgoffice/code/HEAD/tree/trunk/dictionaries/data/bg-en/".chr(27)."[31md$num.txt".chr(27)."[0m?format=raw";
        $content .= mb_convert_encoding(file_get_contents($url), 'utf-8', 'windows-1251')."\n\n";
        $n++;
    }
    $content = mb_substr($content,0,mb_strlen($content)-2);
    echo "\n";
    
    echo "# Creating SQL queries\n";
    
    // SQL to create a temporary table
    $tempSQL = "CREATE TEMPORARY TABLE temptrans(
        name VARCHAR(100) PRIMARY KEY,
        content LONGTEXT
    );";

    // Convert content into an SQL insert statement
    $insertSQL = "INSERT INTO temptrans (name, content)
                VALUES
                ";
    
    $entries = mb_split("\n\n",$content);
    foreach ($entries as $entry) {
        $entrysplit = mb_split("\n",$entry,2);
        if (count($entrysplit) > 1) {
            $insertSQL .= "('".$entrysplit[0]."','".mb_ereg_replace("'","\\'",$entrysplit[1])."'),\n";
        }
    }
    $insertSQL = mb_substr($insertSQL,0,mb_strlen($insertSQL)-2);


    // SQL to empty the word_translation table
    $truncateSQL = "TRUNCATE TABLE word_translation;";

    // SQL to add another name column (this is necessary since some of the translations are of derivative_forms words not present in word)
    $addcolumnSQL = "ALTER TABLE word_translation
                ADD COLUMN name VARCHAR(100);";

    // SQL to insert the new values into word_translation including the word_id where it exists
    $updateSQL = "INSERT INTO word_translation (word_id, lang, content, source, name)
                    SELECT word.id AS word_id, 'ENG' as lang, temptrans.content AS content, 'eurodict' AS source, temptrans.name AS name
                    FROM temptrans LEFT JOIN word ON word.name = temptrans.name
                    GROUP BY temptrans.name
                    ";

    // SQL to drop the temporary table used
    $dropSQL = "DROP TEMPORARY TABLE temptrans;";

    
    echo "# Updating database with SQL";

    // Run SQL queries
    $conn->query($tempSQL);
    $conn->query($insertSQL);
    $conn->query($truncateSQL);
    
    // Only add column if not already present
    $result = $conn->query("SHOW COLUMNS FROM `word_translation` LIKE 'name'", PDO::FETCH_ASSOC);
	$cols = $result->fetchAll();
    $exists = count($cols)?TRUE:FALSE;
    if ($exists) {} else {
        $conn->query($addcolumnSQL);
    }

    $conn->query($updateSQL);
    $conn->query($dropSQL);