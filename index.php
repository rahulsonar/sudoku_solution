<?php
require_once 'sudoku_values.php';
require_once 'SudokuSolution.php';
//print_r($solution);
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        
        <?php
        $sudoku=new SudokuSolution($solution);
        $sudoku->showSudoku();
        ?>
        </div>
    </body>
</html>
