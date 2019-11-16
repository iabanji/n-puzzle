#!/usr/bin/php
<?php

ini_set("memory_limit", -1);

require_once("./src/search_funcs.php");
require_once("./src/heuristic_funcs.php");
require_once("./src/Step.class.php");
require_once("./src/reader.php");
require_once("./src/solvable.php");

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

$size = intval($content[0]);
$N = $size;
if ($size < 3) {
	echo "The size of puzzle must be 3x3 or more big\n";
	exit();
}

$map = $content[1];
//if ((isSolvable($map) == 0 && (($size % 2) == 0)) || (isSolvable($map) != 0 && (($size % 2) != 0))) {
if ((isSolvable($map) != 0 && ($size % 2) != 0) || (isSolvable($map) == 0 && ($size % 2) == 0) && $size < 6) {
	echo "This puzzle is not solvable\n";
	exit();
}

// if (isSolvableEven($map) != 0 && ($size % 2) == 0) {
// 	echo "This puzzle is not solvable\n";
// 	exit();
// }

$trueArr = makeTruePuzzleArray($size);

$openList = new Step();
$openList->just_step = $map;
$openList->koordOfZeroBefore = [-1, -1];

idaSearch($openList, $trueArr);
