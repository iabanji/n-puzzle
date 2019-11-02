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

function isArrayHasOtherArray(array $need, array $stack) {
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
	// for($i = 0; $i < $size; $i++){
	// 	for($j = 0; $j < $size; $j++){
	// 		$arr[$i][$j] = -1;
	// 	}
	// }
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
		echo "\n\n";
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

function makeOpenList(&$steps, $trueArr)
{
	//echo rand(1, 9) . "\n";
	// $posOfDaught = returnKoordDaught(0, $steps->just_step);
	$hashMapBefore = md5(serialize($steps->just_step));
	// $size = count($steps->just_step);

	//print_r(getArraysOfStepsInBestOrder($steps->just_step, $trueArr, $steps->koordOfZeroBefore)); exit();

	$stepsInOrder = getArraysOfStepsInBestOrder($steps->just_step, $trueArr, $steps->koordOfZeroBefore);
	$len = count($stepsInOrder);

	foreach($stepsInOrder as $key => $step) {
			$obj = new Step();
			$obj->just_step = makeStep($step->koordOfZero, $steps->just_step);
			if (isEquelArrays($obj->just_step, $trueArr)) {
				ksort($trueArr);
				print_r($trueArr); exit();
			}
			$hashStep = md5(serialize($obj->just_step));
			if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
				$obj->steps_before = $steps->steps_before;
				$obj->koordOfZeroBefore = $step->koordOfZero;
				array_push($obj->steps_before, $hashMapBefore);
				array_push($steps->next, $obj);
				unset($obj); unset($hashStep);
				makeOpenList($steps->next[count($steps->next) - 1], $trueArr);
			} else {
				unset($obj); unset($hashStep); 
			}
	}



	//for ($i = 0; $i < $n; $i++) {
		
		// if (($posOfDaught[1] - 1) >= 0 && $steps->koordOfZeroBefore[1] !== $posOfDaught[1] - 1) {
		// 	$obj = new Step();
		// 	$obj->just_step = makeStep([$posOfDaught[0], $posOfDaught[1] - 1], $steps->just_step);
		// 	$hashStep = md5(serialize($obj->just_step));
		// 	if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
		// 		$obj->steps_before = $steps->steps_before;
		// 		$obj->koordOfZeroBefore = $posOfDaught;
		// 		array_push($obj->steps_before, $hashMapBefore);
		// 		array_push($steps->next, $obj);
		// 		unset($obj); unset($hashStep);
		// 		makeOpenList($steps->next[count($steps->next) - 1]);
		// 	} else {
		// 		unset($obj); unset($hashStep); 
		// 	}
		// }
		// if ($posOfDaught[1] + 1 < $size && $steps->koordOfZeroBefore[1] !== $posOfDaught[1] + 1) {
		// 	$obj = new Step();
		// 	$obj->just_step = makeStep([$posOfDaught[0], $posOfDaught[1] + 1], $steps->just_step);
		// 	$hashStep = md5(serialize($obj->just_step));
		// 	if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
		// 		$obj->steps_before = $steps->steps_before;
		// 		$obj->koordOfZeroBefore = $posOfDaught;
		// 		array_push($obj->steps_before, $hashMapBefore);
		// 		array_push($steps->next, $obj);
		// 		unset($obj); unset($hashStep);
		// 		makeOpenList($steps->next[count($steps->next) - 1]);
		// 	} else {
		// 		unset($obj); unset($hashStep);
		// 	}
		// }
		// if ($posOfDaught[0] + 1 < $size && $steps->koordOfZeroBefore[0] !== $posOfDaught[0] + 1) {
		// 	$obj = new Step();
		// 	$obj->just_step = makeStep([$posOfDaught[0] + 1, $posOfDaught[1]], $steps->just_step);
		// 	$hashStep = md5(serialize($obj->just_step));
		// 	if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
		// 		$obj->steps_before = $steps->steps_before;
		// 		$obj->koordOfZeroBefore = $posOfDaught;
		// 		array_push($obj->steps_before, $hashMapBefore);
		// 		array_push($steps->next, $obj);
		// 		unset($obj); unset($hashStep);
		// 		makeOpenList($steps->next[count($steps->next) - 1]);
		// 	} else {
		// 		unset($obj); unset($hashStep); 
		// 	}
		// }
		// if ($posOfDaught[0] - 1 >= 0 && $steps->koordOfZeroBefore[0] !== $posOfDaught[0] - 1) {
		// 	$obj = new Step();
		// 	$obj->just_step = makeStep([$posOfDaught[0] - 1, $posOfDaught[1]], $steps->just_step);
		// 	$hashStep = md5(serialize($obj->just_step));
		// 	if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
		// 		$obj->steps_before = $steps->steps_before;
		// 		$obj->koordOfZeroBefore = $posOfDaught;
		// 		array_push($obj->steps_before, $hashMapBefore);
		// 		array_push($steps->next, $obj);
		// 		unset($obj); unset($hashStep);
		// 		makeOpenList($steps->next[count($steps->next) - 1]);
		// 	} else {
		// 		unset($obj); unset($hashStep);
		// 	}
		// }
	//}
}










