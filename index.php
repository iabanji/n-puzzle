#!/usr/bin/php
<?php

//ini_set("memory_limit",-1);

require_once("funcs.php");
require_once("manhattan_funcs.php");
require_once("Step.class.php");

$time_pre = microtime(false);

$size = 3;

$trueArr = makeTruePuzzleArray($size);

$arr = [];
// $arr[0] = [4, 1, 5];
// $arr[1] = [3, 0, 6];
// $arr[2] = [2, 7, 8];
// $arr[0] = [1,0,3];
// $arr[1] = [8,2,4];
// $arr[2] = [7,6,5];
// $arr[0] = [1,2,3];
// $arr[1] = [7,0,4];
// $arr[2] = [6,8,5];

// $arr[0] = [4,3,7];
// $arr[1] = [2,5,8];
// $arr[2] = [0,1,6];

$arr[0] = [1,2,3];
$arr[1] = [7,0,4];
$arr[2] = [6,8,5];

$openList = new step();
$openList->just_step = $arr;

makeOpenList($openList);

//print_r($openList);

