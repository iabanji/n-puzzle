<?php

require_once("funcs.php");

function countDistance(array $koord1, array $koord2): int
{
	$x = $koord1[0] - $koord2[0];
	if ($x < 0)
		$x = -$x;
	$y = $koord1[1] - $koord2[1];
	if ($y < 0)
		$y = -$y;

	return ($x + $y);
}

function getCost(array $now, array $arr): int
{
	$cost = 0;
	$len = count($now);
	for($i = 0; $i < $len; $i++){
		for($j = 0; $j < count($now[$i]); $j++){
			$cost += countDistance([$i,$j], returnKoordDaught($now[$i][$j], $arr));
		}
	}

	return $cost;
}

function returnLessKoords(array $koord, array $arr, array $trueArr, array $all): array
{
	$x = -1;
	$y = -1;
	$bestCost = -1;
	$tmp;

	if ($koord[0] + 1 < count($arr)){
		$tmp = makeStep([$koord[0] + 1, $koord[1]], $arr);
		$cost = getCost($tmp, $trueArr);
		if (($cost < $bestCost || $bestCost == -1) && !in_array($tmp, $all)){
			$x = $koord[0] + 1;
			$y = $koord[1];
			$bestCost = $cost;
		}
		unset($tmp);
	}
	if ($koord[0] - 1 >= 0){
		$tmp = makeStep([$koord[0] - 1, $koord[1]], $arr);
		$cost = getCost($tmp, $trueArr);
		if (($cost < $bestCost || $bestCost == -1) && !in_array($tmp, $all)){
			$x = $koord[0] - 1;
			$y = $koord[1];
			$bestCost = $cost;
		}
		unset($tmp);
	}
	if ($koord[1] + 1 < count($arr)){
		$tmp = makeStep([$koord[0], $koord[1] + 1], $arr);
		$cost = getCost($tmp, $trueArr);
		if (($cost < $bestCost || $bestCost == -1) && !in_array($tmp, $all)){
			$x = $koord[0];
			$y = $koord[1] + 1;
			$bestCost = $cost;
		}
		unset($tmp);
	}
	if ($koord[1] - 1 >= 0){
		$tmp = makeStep([$koord[0], $koord[1] - 1], $arr);
		$cost = getCost($tmp, $trueArr);
		if (($cost < $bestCost || $bestCost == -1) && !in_array($tmp, $all)){
			$x = $koord[0];
			$y = $koord[1] - 1;
			$bestCost = $cost;
		}
		unset($tmp);
	}

	return [$x, $y];
}