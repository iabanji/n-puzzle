<?php 
//PHP  program to check if a given instance of N*N-1 
// puzzle is solvable or not
  
// A utility function to count inversions in given 
// array 'arr[]'. Note that this function can be 
// optimized to work in O(n Log n) time. The idea 
// here is to keep code small and simple. 
  
// function  getInvCount($arr) 
// { 
//     global $N; 
//     $inv_count = 0; 
//     for ($i = 0; $i < $N * $N - 1; $i++) 
//     { 
//         for ($j = $i + 1; $j < $N * $N; $j++) 
//         { 
//             // count pairs(i, j) such that i appears 
//             // before j, but i > j. 
//             $inv_count++; 
//         } 
//     } 
//     return $inv_count; 
// } 
  
// // find Position of blank from bottom 
// function findXPosition($puzzle) 
// { 
//     global $N; 
//     // start from bottom-right corner of matrix 
//     for ($i = $N - 1; $i >= 0; $i--)  {
//         for ($j = $N - 1; $j >= 0; $j--) {
//             if ($puzzle[$i][$j] == 0) {
//                 return $N - $i; 
//             }
//         }
//     }
// } 
  
// // This function returns true if given 
// // instance of N*N - 1 puzzle is solvable 
// function  isSolvableEven($puzzle) 
// { 
//     global $N; 
//     // Count inversions in given puzzle 
//     $invCount = getInvCount($puzzle); 
//     //var_dump($N & 1); 4 & 1 = 0; 5 & 1 = 1
  
//     // If grid is odd, return true if inversion 
//     // count is even. 
//     if ($N & 1) {
//         return !($invCount & 1); 
//     } else { // grid is even 
//         $pos = findXPosition($puzzle); 
//         if ($pos & 1) {
//             return !($invCount & 1); 
//         } else {
//             return ($invCount & 1); 
//         }
//     } 
// }

//--------------------------------------------------

function isSolvable(array $p)
{
    global $N;

    $puzzle = [];
    array_walk_recursive($p, function ($item, $key) use (&$puzzle) {
        $puzzle[] = $item;    
    });

    $parity = 0;
    $gridWidth = $N;
    $row = 0; // the current row we are on
    $blankRow = 0; // the row with the blank tile

    for ($i = 0; $i < $N * $N; $i++)
    {
        if ($i % $gridWidth == 0) { // advance to next row
            $row++;
        }
        if ($puzzle[$i] == 0) { // the blank tile
            $blankRow = $row; // save the row on which encountered
            continue;
        }
        for ($j = $i + 1; $j < $N * $N; $j++)
        {
            if ($puzzle[$i] > $puzzle[$j] && $puzzle[$j] != 0)
            {
                $parity++;
            }
        }
    }

    if ($gridWidth % 2 == 0) { // even grid
        if ($blankRow % 2 == 0) { // blank on odd row; counting from bottom
            return $parity % 2 == 0;
        } else { // blank on even row; counting from bottom
            return $parity % 2 != 0;
        }
    } else { // odd grid
        return $parity % 2 == 0;
    }
}
