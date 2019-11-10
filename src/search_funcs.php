<?php

function isEquelArrays(array $arr1, array $arr2): bool
{
	if (count($arr1) != count($arr2))
		return false;

	$len = count($arr1);

	for($i = 0; $i < $len; $i++)
	{
		if (count($arr1[$i]) != count($arr2[$i]))
			return false;
		for($j = 0; $j < count($arr1[$i]); $j++){
			if ($arr1[$i][$j] != $arr2[$i][$j])
				return false;
		}
	}

	return true;
}

function isArrayHasOtherArray(array $need, array $stack): bool
{
	$len = count($stack);

	for($i = 0; $i < $len; $i++) {
		if (isEquelArrays($need, $stack[$i])) {
			return true;
		}
	}
	return false;
}

function makeTruePuzzleArray(int $size): array
{
	$arr = [];
	for($m = 0; $m < $size; $m++){
		for($n = 0; $n < $size; $n++){
			$arr[$m][$n] = NULL;
		}
	}
	$a = 2;
	$arr[0][0] = 1;
	$q = $size * $size;
	$x = 0;
	$y = 0;
	$follow = 'r';

	$i = 0;
	while ($i < $q)
	{
		if (!isset($arr[$x][$y+1]) && (($y + 1) < $size) && ($follow == 'r' || $follow == 'a')) {
			++$y;
			$arr[$x][$y] = $a;
			if ($a == $q)
				$arr[$x][$y] = 0;
			$follow = 'r';
			if (isset($arr[$x][$y + 1]) || $y + 1 >= $size)
				$follow = 'a';
		}
		else if (!isset($arr[$x+1][$y]) && (($x + 1) < $size) && ($follow == 'd' || $follow == 'a')) {
			++$x;
			$arr[$x][$y] = $a;
			if ($a == $q)
				$arr[$x][$y] = 0;
			$follow = 'd';
			if (isset($arr[$x+1][$y]) || $x + 1 >= $size)
				$follow = 'a';
		}
		else if (!isset($arr[$x][$y-1]) && (($y - 1) >= 0) && ($follow == 'l' || $follow == 'a')) {
			--$y;
			$arr[$x][$y] = $a;
			if ($a == $q)
				$arr[$x][$y] = 0;
			$follow = 'l';
			if (isset($arr[$x][$y - 1]) || $y - 1 < 0)
				$follow = 'a';
		}
		else if (!isset($arr[$x-1][$y]) && (($x - 1) >= 0) && ($follow == 'u' || $follow == 'a')) {
			--$x;
			$arr[$x][$y] = $a;
			if ($a == $q)
				$arr[$x][$y] = 0;
			$follow = 'u';
			if (isset($arr[$x-1][$y]) || $x - 1 < 0)
				$follow = 'a';
		}
		
		$a++; $i++;
	}
	return $arr;
}

function printArray(array $arr)
{
	for ($i = 0; $i < count($arr); $i++)
	{
		for ($j = 0; $j < count($arr[$i]); $j++){
			echo $arr[$i][$j];
			if ($arr[$i][$j] < 10)
				echo "   ";
			else
				echo "  ";
		}
		echo "\n";
	}
}

function returnKoordDaught(int $symbol, array $arr): array
{
	for ($i = 0; $i < count($arr); $i++)
	{
		for ($j = 0; $j < count($arr[$i]); $j++){
			if ($arr[$i][$j] == $symbol){
				return [$i, $j];
			}
		}
	}

	return [-1, -1];
}

function makeStep(array $koordsTo, array $arr): array
{
	$tmp = returnKoordDaught(0, $arr);
	$arr[$tmp[0]][$tmp[1]] = $arr[$koordsTo[0]][$koordsTo[1]];
	$arr[$koordsTo[0]][$koordsTo[1]] = 0;

	return $arr;
}

function getArraysOfStepsInBestOrder($table, $trueArr, $stepBefore)
{
	$size = count($table);
	$posOfDaught = returnKoordDaught(0, $table);
	$stepsInOrder = [];

	if (($posOfDaught[1] - 1) >= 0 && $stepBefore !== [$posOfDaught[0], $posOfDaught[1] - 1]) {
		$st = json_decode('{}');
		$step = makeStep([$posOfDaught[0], $posOfDaught[1] - 1], $table);
		$st->koordOfZero = [$posOfDaught[0], $posOfDaught[1] - 1];
		$st->cost = getCost($step, $trueArr);
		$stepsInOrder[] = $st;
	}
	if ($posOfDaught[1] + 1 < $size && $stepBefore !== [$posOfDaught[0], $posOfDaught[1] + 1]) {
		$st = json_decode('{}');
		$step = makeStep([$posOfDaught[0], $posOfDaught[1] + 1], $table);
		$st->koordOfZero = [$posOfDaught[0], $posOfDaught[1] + 1];
		$st->cost = getCost($step, $trueArr);
		$stepsInOrder[] = $st;
	}
	if ($posOfDaught[0] + 1 < $size && $stepBefore !== [$posOfDaught[0], $posOfDaught[1] + 1]) {
		$st = json_decode('{}');
		$step = makeStep([$posOfDaught[0] + 1, $posOfDaught[1]], $table);
		$st->koordOfZero = [$posOfDaught[0] + 1, $posOfDaught[1]];
		$st->cost = getCost($step, $trueArr);
		$stepsInOrder[] = $st;
	}
	if ($posOfDaught[0] - 1 >= 0 && $stepBefore !== [$posOfDaught[0] - 1, $posOfDaught[1]]) {
		$st = json_decode('{}');
		$step = makeStep([$posOfDaught[0] - 1, $posOfDaught[1]], $table);
		$st->koordOfZero = [$posOfDaught[0] - 1, $posOfDaught[1]];
		$st->cost = getCost($step, $trueArr);
		$stepsInOrder[] = $st;
	}

	$len = count($stepsInOrder) - 1;
	for ($i = 0; $i < $len; $i++) {
		if ($stepsInOrder[$i]->cost > $stepsInOrder[$i + 1]->cost) {
			$tmp = $stepsInOrder[$i];
			$stepsInOrder[$i] = $stepsInOrder[$i + 1];
			$stepsInOrder[$i + 1] = $tmp;
			$i = -1;
		}
	}

	return $stepsInOrder;
}

function idasearch(&$steps, $trueArr)
{
	$GLOBALS['closes'][] = $steps;
	if ($steps->just_step === $trueArr) {
		$GLOBALS['closes'][] = $steps;
		finalFunc();
		exit();
	}

	$hashMapBefore = md5(serialize($steps->just_step));
	$stepsInOrder = getArraysOfStepsInBestOrder($steps->just_step, $trueArr, $steps->koordOfZeroBefore);

	if (count($stepsInOrder) > 1) {
		$s = new Step();
		$s->just_step = makeStep($stepsInOrder[0]->koordOfZero, $steps->just_step);
		$cost = $s->cost;

		if ($s->just_step === $trueArr) {
			$GLOBALS['closes'][] = $s;
			finalFunc();
			exit();
		}

		$len = count($stepsInOrder);

		for ($i = 1; $i < $len; $i++) {
			$s = new Step();
			$s->just_step = makeStep($stepsInOrder[$i]->koordOfZero, $steps->just_step);

			if ($s->just_step === $trueArr) {
				$GLOBALS['closes'][] = $s;
				finalFunc();
				exit();
			} else if ($cost == $stepsInOrder[$i]->cost) {
				$GLOBALS['opens'][] = $s;
			}

		}
	}
	
	foreach($stepsInOrder as $key => $step) {
		$obj = new Step();
		$obj->just_step = makeStep($step->koordOfZero, $steps->just_step);
		if ($obj->just_step === $trueArr) {
			$GLOBALS['closes'][] = $obj;
			finalFunc();
			exit();
		}
		$hashStep = md5(serialize($obj->just_step));
		if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
			$obj->steps_before = $steps->steps_before;
			$obj->koordOfZeroBefore = $step->koordOfZero;
			array_push($obj->steps_before, $hashMapBefore);
			array_push($steps->next, $obj);
			unset($obj); unset($hashStep);
			idasearch($steps->next[count($steps->next) - 1], $trueArr);
		} else {
			unset($obj); unset($hashStep); 
		}
	}
}


