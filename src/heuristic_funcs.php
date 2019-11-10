<?php

function countDistanceEuclide(array $koord1, array $koord2): int
{
	return sqrt(pow($koord1[0] - $koord2[0], 2) + pow($koord1[1] - $koord2[1], 2));
}

function countDistanceOnPlace(array $koord1, array $koord2): int
{
	$x = $koord1[0] - $koord2[0];
	if ($x < 0)
		$x = -$x;
	$y = $koord1[1] - $koord2[1];
	if ($y < 0)
		$y = -$y;

	return ($x + $y);
}

function makeManhattan(array $map, array $koord)
{
	$man = [];
	$len = count($map);
	for($i = 0; $i < $len; $i++){
		$man[$i] = [];
		for($j = 0; $j < $len; $j++){
			$man[$i][$j] = countDistanceOnPlace([$i, $j], $koord);
		}
	}

	return $man;
}

function countDistanceManhattan(array $koord1, array $koord2, array $map): int
{
	$manhattanMap = makeManhattan($map, $koord2);

	return $manhattanMap[$koord1[0]][$koord1[1]];
}

function getCost(array $now, array $trueArr): int
{
	$cost = 0;
	$len = count($now);
	for($i = 0; $i < $len; $i++){
		for($j = 0; $j < count($now[$i]); $j++){
			if ($GLOBALS['heuristicFunc'] == '-e') {
				$cost += countDistanceEuclide([$i,$j], returnKoordDaught($now[$i][$j], $trueArr));
			} else if ($GLOBALS['heuristicFunc'] == '-t') {
				$cost += countDistanceOnPlace([$i,$j], returnKoordDaught($now[$i][$j], $trueArr));
			} else {
				$cost += countDistanceManhattan([$i,$j], returnKoordDaught($now[$i][$j], $trueArr), $now);
			}
		}
	}

	return $cost;
}
