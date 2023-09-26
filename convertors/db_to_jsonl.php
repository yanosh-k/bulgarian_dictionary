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
	
	
	// Word extracting query
	$wordsSql = "SELECT
	`word`.`id`,
	`word`.`name`,
	`word`.`meaning`,
	GROUP_CONCAT(DISTINCT `derivative_form`.`name` ORDER BY derivative_form.`name` ASC SEPARATOR ' @AND@ ') AS `derivative_forms`
FROM
	`word`
LEFT JOIN `derivative_form` ON `derivative_form`.`base_word_id` = `word`.`id`
WHERE
	`word`.`meaning` IS NOT NULL
GROUP BY `word`.`id`
ORDER BY `word`.`name` ASC, `word`.`id` ASC
";
	
	
	// Load words data
	ini_set('memory_limit', '512M');
	$conn->query("SET SESSION group_concat_max_len = 1000000;");
	$wordsRes = $conn->query($wordsSql, PDO::FETCH_ASSOC);
	$words = $wordsRes->fetchAll();
	
	
	// Add the word as an key for all words data
	$keyedWords = [];
	foreach ($words as $singleWordData) {
		if (!isset($keyedWords[$singleWordData['name']])) {
			$keyedWords[$singleWordData['name']] = $singleWordData;
		}
	}
	
	
	// Holds a list of all words
	$outputDataFile = fopen('bulgarian_dictionary.jsonl', 'w');
	
	
	// Go trough all words and save the to a .txt file
	foreach ($keyedWords as $singleKeyedWord) {
		
		$dataToWrite = [
			'base_word' => $singleKeyedWord['name'],
			'id' => $singleKeyedWord['id'],
			'meaning' => format_meaning($singleKeyedWord['meaning'], $keyedWords),
			'derivative_forms' => $singleKeyedWord['derivative_forms'] ? array_filter(explode(' @AND@ ', $singleKeyedWord['derivative_forms'])) : []
		];
		
		// Add the word data as a JSON object to the file
		fwrite($outputDataFile, json_encode($dataToWrite) . "\n");
	}
	
	// Close the file handle
	fclose($outputDataFile);
	
	
	
	// Lib functions
	///////////////////////////////////////////////////////////////////////////
	function format_meaning($meaning, &$wordsMap)
	{
		$meaning = preg_replace('/#(\d+)/', '<b>$1.</b>', $meaning);
		$meaning = preg_replace('/__([^_]+)__/U', '<b>$1</b>', $meaning);
		$meaning = preg_replace('/_([^_]+)_/U', '<i>$1</i>', $meaning);

		$meaning = strtr($meaning, array(
			'+мн.' => '<abbr title="множествено число">мн.</abbr>',
			'+ед.' => '<abbr title="единствено число">ед.</abbr>',
			'+м.' => '<abbr title="мъжки род">м.</abbr>',
			'+ж.' => '<abbr title="женски род">ж.</abbr>',
			'+ср.' => '<abbr title="среден род">ср.</abbr>',
			'+мин. несв.' => '<abbr title="минало несвършено време">мин. несв.</abbr>',
			'+мин. св.' => '<abbr title="минало свършено време">мин. св.</abbr>',
			'+мин. прич.' => '<abbr title="минало причастие">мин. прич.</abbr>',
			'+несв.' => '<abbr title="несвършен вид">несв.</abbr>',
			'+св.' => '<abbr title="свършен вид">св.</abbr>',
			'+същ.' => '<abbr title="съществително име">същ.</abbr>',
			'+прил.' => '<abbr title="прилагателно име">прил.</abbr>',
			'+Прен.' => '<abbr title="В преносен смисъл">Прен.</abbr>',
			'+Пренебр.' => '<abbr title="Пренебрежително">Пренебр.</abbr>',
			'+Разг.' => '<abbr title="Разговорно">Разг.</abbr>',
			'+Спец.' => '<abbr title="Специализирано">Спец.</abbr>',
			'+вж.' => '<abbr title="виж">вж.</abbr>',
			'+мат.' => '<abbr title="В математиката">мат.</abbr>',
			'+Филос.' => '<abbr title="Във философията">Филос.</abbr>',
			'`' => '&#768;',
			"\n" => "<br>",
			"----\n" => '<hr>',
			'*' => '•',
		));
	
	
		// Italic
		$meaning = preg_replace('/\+(\S[^.]+\.)/U', '<i>$1</i>', $meaning);


		// Wiki link
		$meaning = preg_replace_callback('/\[\[w:([^]]+)\]\]/', function($matches) {
			return sprintf('<a href="https://bg.wikipedia.org/wiki/%s"><i>%s</i> в Уикипедия</a>',
				urlencode(str_replace(' ', '_', $matches[1])),
				$matches[1]);
		}, $meaning);
		
		
		// Cross-reference
		$meaning = preg_replace_callback('/\[\[([^]]+?)\]\]/', function($matches) use (&$wordsMap) {
			$wordText = str_replace('&#768;', '', $matches[1]);

			if (isset($wordsMap[$wordText])) {
				$out = '<a href="#' . $wordsMap[$wordText]['id'] . '">' . $wordText . '</a>';
			} else {
				$out = $matches[1];
			}
			
			return $out;
		}, $meaning);

		return $meaning;
	}