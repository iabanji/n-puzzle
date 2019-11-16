<?php

function reedFile(string $path)
{
	if (!file_exists($path)) {
		return false;
	}
	$content = file_get_contents($path);

	if (!$content) {
		return false;
	}

	$strings = array_values(array_filter(explode("\n", $content)));

	if (!is_numeric($strings[0])) {
		return false;
	}
	$size = intval($strings[0]);
	$map = [];
	$valiadtion = [];

	for ($i = 1, $len = count($strings); $i < $len; $i++) {
		$map[$i - 1] = [];
		$cifers = array_values(array_filter(explode(" ", $strings[$i]), function($value) { return !is_null($value) && $value !== ''; }));
		if (count($cifers) !== $size) {
				print_r($cifers);
			return false;
		}
		foreach($cifers as $key => $cifer) {
			if (!is_numeric($cifer)) {
				return false;
			}
			$cifer = intval($cifer);
			if (!in_array($cifer, $valiadtion)) {
				$map[$i - 1][$key] = $cifer;
			} else {
				return false;
			}
			$validation[] = $cifer;
		}
	}

	return [$size, $map];
}

function usage()
{
	echo "usage: ./n-pazzle.php [-e] [-t] [-e] file\n\n";
	echo "arguments:\n-m			manhattan distance\n";
	echo "-e  			euclidean distance\n";
	echo "-t  			tiles out-of-place count distance\n";
	echo "file          		input file\n";
}

function finalFunc()
{
	$timeEnd = microtime(false);

	echo "Timer: " . ($timeEnd - $GLOBALS['time_pre']) . "sec\n";

	echo "Total number of states ever selected in the 'opened' set: " . count($GLOBALS['opens']) . "\n";
	echo "Maximum number of states ever represented in memory at the same time
during the search: " . (count($GLOBALS['opens']) + count($GLOBALS['closes'])) . "\n";
	echo "Number of moves required to transition from the initial state to the final state,
according to the search: " . count($GLOBALS['closes']) . "\n";
	echo "The ordered sequence of states that make up the solution write to file: 'results.txt'\n";

	$fp = fopen('results.txt', 'w');
	$strToFile = '';
	foreach($GLOBALS['closes'] as $step) {
		foreach($step->just_step as $str) {
			foreach($str as $val) {
				$strToFile = $strToFile . $val . "  ";
			}
			$strToFile = $strToFile . "\n";
		}
		$strToFile = $strToFile . "\n";
	}
	fwrite($fp, $strToFile);
	fclose($fp);
}
