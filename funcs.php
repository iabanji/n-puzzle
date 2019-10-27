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

function makeOpenList(&$steps)
{
	echo rand(1, 100)."\n";
	$n = 4;
	$posOfDaught = returnKoordDaught(0, $steps->just_step);
	$hashMapBefore = md5(serialize($steps->just_step));
	$size = count($steps->just_step);

	//for ($i = 0; $i < $n; $i++) {
		$obj = new Step();
		if ($posOfDaught[1] - 1 >= 0) {
			$obj->just_step = makeStep([$posOfDaught[0], $posOfDaught[1] - 1], $steps->just_step);
			$hashStep = md5(serialize($obj->just_step));
			if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
				$obj->steps_before = $steps->steps_before;
				array_push($obj->steps_before, $hashMapBefore);
				array_push($steps->next, $obj);
				makeOpenList($steps->next[count($steps->next) - 1]/*, $obj->just_step, $obj->steps_before*/);
			}
		}
		if ($posOfDaught[1] + 1 < $size) {
			$obj->just_step = makeStep([$posOfDaught[0], $posOfDaught[1] + 1], $steps->just_step);
			$hashStep = md5(serialize($obj->just_step));
			if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
				$obj->steps_before = $steps->steps_before;
				array_push($obj->steps_before, $hashMapBefore);
				array_push($steps->next, $obj);
				makeOpenList($steps->next[count($steps->next) - 1]/*, $obj->just_step, $obj->steps_before*/);
			}
		}
		if ($posOfDaught[0] + 1 < $size) {
			$obj->just_step = makeStep([$posOfDaught[0] + 1, $posOfDaught[1]], $steps->just_step);
			$hashStep = md5(serialize($obj->just_step));
			if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
				$obj->steps_before = $steps->steps_before;
				array_push($obj->steps_before, $hashMapBefore);
				array_push($steps->next, $obj);
				makeOpenList($steps->next[count($steps->next) - 1]/*, $obj->just_step, $obj->steps_before*/);
			}
		}
		if ($posOfDaught[0] - 1 >= 0) {
			$obj->just_step = makeStep([$posOfDaught[0] - 1, $posOfDaught[1]], $steps->just_step);
			$hashStep = md5(serialize($obj->just_step));
			if (!in_array($hashStep, $steps->steps_before) && $hashStep !== $hashMapBefore) {
				$obj->steps_before = $steps->steps_before;
				array_push($obj->steps_before, $hashMapBefore);
				array_push($steps->next, $obj);
				makeOpenList($steps->next[count($steps->next) - 1]/*, $obj->just_step, $obj->steps_before*/);
			}
		}
		//print_r($posOfDaught); printArray($obj->just_step); exit();
		unset($obj);
	//}
}










