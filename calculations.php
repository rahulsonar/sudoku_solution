<?php

require_once 'sudoku_values.php';
require_once 'SudokuSolution.php';
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$sudoku=new SudokuSolution($solution);
$sudoku->doCalculations();
$sudoku->showSudoku();

/*asort($missing_in_rows);
asort($missing_in_columns);
echo '<pre>';
print_r($missing_in_rows);
print_r($missing_in_columns);
*/