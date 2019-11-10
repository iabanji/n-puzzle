#!/usr/bin/php
<?php

ini_set("memory_limit", -1);

require_once("./src/search_funcs.php");
require_once("./src/heuristic_funcs.php");
require_once("./src/Step.class.php");
require_once("./src/reader.php");

$distances = ['-t', '-e', '-m'];

if ($argc != 3 || !in_array($argv[1], $distances)) {
	usage();
	exit();
}

$heuristicFunc = $argv[1];

$time_pre = microtime(false);

$content = reedFile($argv[2]);

if (!$content) {
	echo "file error\n";
	exit();
}

$opens = [];
$closes = [];

$size = $content[0];
$map = $content[1];

$trueArr = makeTruePuzzleArray($size);

$openList = new Step();
$openList->just_step = $map;
$openList->koordOfZeroBefore = [-1, -1];

idaSearch($openList, $trueArr);

// $fp = fopen('res.json', 'w');
// fwrite($fp, json_encode($openList));
// fclose($fp);
