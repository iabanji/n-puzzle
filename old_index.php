#!/usr/bin/php
<?php

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

$allSteps = [];
array_unshift($allSteps, $arr);


$arr1 = $arr;
$i = 0;

// while (!isEquelArrays($arr1, $trueArr))
// {
// 	$koordsOfZero = returnKoordDaught(0, $arr1);
// 	$koord = returnLessKoords($koordsOfZero, $arr1, $trueArr, $allSteps);
// 	if ($koord === [-1,-1])
// 	{
// 		echo "bad puzzle\n";
// 		echo $i . " step number\n";
// 		printArray($arr1);
// 		exit();
// 	}
// 	printArray($arr1);
// 	echo "----------------------\n";
// 	$arr1 = makeStep($koord, $arr1);
// 	if(!in_array($arr1, $allSteps))
// 		array_unshift($allSteps, $arr1);

// 	// if($i == 11001)
// 	// 	break;

// 	$i++;
// }

// printArray($arr1);

// $time_post = microtime(false);
// $exec_time = $time_post - $time_pre;

// echo "$exec_time\n";


function a($arr, $trueArr)
{
	$allS = [];
	if (isEquelArrays($arr, $trueArr)) {
		printArray($arr);
		exit();
	}

	$koordsOfZero = returnKoordDaught(0, $arr);
	$koord = returnLessKoords($koordsOfZero, $arr, $trueArr, $allS);
	$arr1 = makeStep($koord, $arr);
	if(!in_array($arr1, $allS))
		array_unshift($allS, $arr1);

	if (isEquelArrays($arr, $trueArr)) {
		printArray($arr);
		exit();
	}
	else if ($koord === [-1,-1])
	{
		$koordsOfZero = returnKoordDaught(0, $arr);
		$koord = returnLessKoords($koordsOfZero, $arr, $trueArr, $allS);
		if ($koord === [-1,-1])
		{
			a($arr,$trueArr);
		}
		$arr1 = makeStep($koord, $arr);
		a($arr1,$trueArr);
	}
	else
	{
		$arr = $arr1;
		a($arr,$trueArr);
	}

	$i++;
}

$res = a($arr, $trueArr);


