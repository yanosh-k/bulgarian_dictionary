<?php
	
	// Read options
	$cliOptions = getopt('i:');
	$inputFile = $cliOptions['i'];
	
	
	// Open the input file for reading
	$inputFileHandle = fopen($inputFile, 'r');
	if (!$inputFileHandle) {
		die('Error while trying to read input file');
	}
	
	
	// Define the number of words a single HTML would contain
	$wordsPerHtml = 5000;
	$wordsCounter = 1;
	$htmlsCounter = 0;
	
	
	// Read $wordsPerHtml words and put them in to an HTML file
	$wordsDataStack = [];
	while(true) {
		$singleWordJson = fgets($inputFileHandle);
		
		// Parse the word if not EOF
		if ($singleWordJson) {
			$singleWordData = json_decode(trim($singleWordJson), true);
			$wordsDataStack[] = $singleWordData;
		}
		
		
		// Save to file
		if (!$singleWordJson || $wordsCounter === $wordsPerHtml) {
			// Create the actual file
			file_put_contents(
				dirname(__DIR__) . "/opf/bg_en_dictionary{$htmlsCounter}.html", 
				renderPhpToString(__DIR__ . '/templates/html.php', ['words' => $wordsDataStack])
			);
			
			// Prepare for the next round of loops
			$wordsDataStack = [];
			$wordsCounter = 1;
			++$htmlsCounter;
		}
		
		
		// Reached EOF so stop execution
		if (!$singleWordJson) {
			fclose($inputFileHandle);
			break;
		}
		
		++$wordsCounter;
	}
	
	// Create the OPF file
	file_put_contents(
		dirname(__DIR__) . "/opf/bg_en_dictionary.opf", 
		renderPhpToString(__DIR__ . '/templates/bg_en_opf.php', ['manifestItemsCount' => $htmlsCounter])
	);
	
	
	/**
	 * Renders a php file into a string
	 *
	 * @param string $filePath the full path to the file
	 * @param array $data the data that the file will use in its scope
	 * @return string
	 */
	function renderPhpToString()
	{
		if (func_num_args() === 2 && is_array(func_get_arg(1)))
		{
			extract(func_get_arg(1));
		}
		ob_start();
		require func_get_arg(0);
		return ob_get_clean();
	}