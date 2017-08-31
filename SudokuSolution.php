<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SudokuSolution {

    public function __construct($solution) {
        $this->solution = $solution;
        $this->base_value = $solution;
        $this->setMissingValues();
    }

    public $base_value;
    public $columns;
    public $counter = 0;
    public $numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9];
    public $grouping = [
        [0, 1, 2],
        [3, 4, 5],
        [6, 7, 8]
    ];

    function getThreeArrayDiff($array1, $array2, $array3) {
        //echo "Intersection<br />";
        $a = array_intersect($array1, $array2);
        //echo "<br />";
        //print_r($a);
        $tmp = array();
        foreach ($array1 as $a) {
            foreach ($array2 as $b) {
                if ($a == $b) {
                    $tmp[] = $a;
                }
            }
        }
        $final = array();
        foreach ($array3 as $c) {
            foreach ($tmp as $t) {
                if ($c == $t) {
                    $final[] = $c;
                }
            }
        }
        return $final;
    }

    function getCommonNumbers($array1, $array2, $array3) {

        //echo "Let me show all the arrays: <br /><pre>";
        /* print_r($array1);
          print_r($array2);
          print_r($array3);
          print_r($this->columns[7]); */
        $tmp1 = array_merge($array1, $array2);
        //echo "We merged array 1 and 2<br /><pre>";
        $tmp1 = array_unique($tmp1);
        $tmp2 = array_merge($array3, $tmp1);
        $tmp2 = array_unique($tmp2);
        //print_r($tmp2);
        if (count($tmp2) == 9) {
            //echo "We have some success<br />";
            $numbers = array_diff($this->numbers, $tmp2);
            //var_dump($numbers);
            if (count($numbers) == 1) {
                foreach ($numbers as $number) {
                    //var_dump($number);
                    return $number;
                }
            }
        }
        return false;
        //echo "<br />";
    }

    function setSingleValuesColumns() {
        //echo "<br />setSingleValuesColumns<br />";

        for ($i = 0; $i < 9; $i++) {
            //echo "<br />We are in column $i<br />";
            $zeros = array();
            foreach ($this->columns[$i] as $key => $n) {
                if ($n == 0) {
                    $zeros[] = $key;
                }
            }
            if (count($zeros) == 1) {
                //echo "<br />Yes, there is a single value possible<br />";
                $missing_index = $zeros[0];
                foreach ($this->numbers as $n1) {
                    if (!in_array($n1, $this->columns[$i])) {
                        //echo "Missing number is $n1 at row $missing_index and column $i<br />";
                        $this->setSolutionValue($missing_index, $i, $n1, __LINE__);
                        //$this->solution[$missing_index][$i]=$n1;
                    }
                }
            }
        }
        //echo "<br />setSingleValuesColumns ends <hr />";
    }

    function setSingleValuesRows() {
        //echo "<br />setSingleValuesRows<br />";

        for ($i = 0; $i < 9; $i++) {
            //echo "<br />We are in row $i<br />";
            $zeros = array();
            foreach ($this->solution[$i] as $key => $n) {
                if ($n == 0) {
                    $zeros[] = $key;
                }
            }
            if (count($zeros) == 1) {
                //echo "<br />Single value in row found<br />";
                //print_r($this->solution[$i]);
                //echo "<br />Yes, there is a single value possible<br />";
                $missing_index = $zeros[0];
                foreach ($this->numbers as $n1) {
                    if (!in_array($n1, $this->solution[$i])) {
                        //echo "Missing number is $n1 at row $missing_index and column $i<br />";
                        $this->setSolutionValue($i, $missing_index, $n1, __LINE__);
                        //$this->solution[$missing_index][$i]=$n1;
                    }
                }
            }
        }
        //echo "<br />setSingleValuesRows ends <hr />";
    }

    function setSingleValuesBoxes() {
        //echo "<br />setSingleValuesRows<br />";

        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                //echo "<br />We are in box $i--$j<br />";
                $box = $this->boxes[$i][$j];
                $zeros = array();
                foreach ($box as $key => $n) {
                    if ($n == 0) {
                        $zeros[] = $key;
                    }
                }
                if (count($zeros) == 1) {
                    //print_r($zeros);
                    //echo "Box:: <br /><pre>";
                    //print_r($box);
                    //  echo "<br />Single value in row found<br />";
                    //echo "<br />Yes, there is a single value possible<br />";
                    $missing_index = $zeros[0];

                    foreach ($this->numbers as $n1) {
                        if (!in_array($n1, $box)) {

                            //echo "Missing number is $n1 at row $missing_index and column $i<br />";
                            $indexes = $this->getBoxInsideIndexes($i, $j, $missing_index);

                            $this->setSolutionValue($indexes[0], $indexes[1], $n1, __LINE__);
                            //$this->solution[$missing_index][$i]=$n1;
                        }
                    }
                }
            }
        }
    }

    function getBoxInsideIndexes($i, $j, $n) {
        //echo "Indexes requested : $i, $j, $n<br/>";
        $indexes = [
            [
                [
                    [0, 0], [0, 1], [0, 2], [1, 0], [1, 1], [1, 2], [2, 0], [2, 1], [2, 2]
                ],
                [
                    [0, 3], [0, 4], [0, 5], [1, 3], [1, 4], [1, 5], [2, 3], [2, 4], [2, 5]
                ],
                [
                    [0, 6], [0, 7], [0, 8], [1, 6], [1, 7], [1, 8], [2, 6], [2, 7], [2, 8]
                ]
            ],
            [
                [
                    [3, 0], [3, 1], [3, 2], [4, 0], [4, 1], [4, 2], [5, 0], [5, 1], [5, 2]
                ],
                [
                    [3, 3], [3, 4], [3, 5], [4, 3], [4, 4], [4, 5], [5, 3], [5, 4], [5, 5]
                ],
                [
                    [3, 6], [3, 7], [3, 8], [4, 6], [4, 7], [4, 8], [5, 6], [5, 7], [5, 8]
                ]
            ],
            [
                [
                    [6, 0], [6, 1], [6, 2], [7, 0], [7, 1], [7, 2], [8, 0], [8, 1], [8, 2],
                ],
                [
                    [6, 3], [6, 4], [6, 5], [7, 3], [7, 4], [7, 5], [8, 3], [8, 4], [8, 5]
                ],
                [
                    [6, 6], [6, 7], [6, 8], [7, 6], [7, 7], [7, 8], [8, 6], [8, 7], [8, 8]
                ]
            ]
        ];
        //echo "<pre>";
        //print_r($indexes);
        return $indexes[$i][$j][$n];
    }

    function getBoxByPosition($position) {
        $row = $position[0];
        $column = $position[1];


        if ($row < 3 && $column < 3) {
            return $this->boxes[0][0];
        }
        if ($row < 3 && ($column >= 3 && $column < 6)) {
            return $this->boxes[0][1];
        }
        if ($row < 3 && ($column >= 6)) {
            return $this->boxes[0][2];
        }
        if (($row >= 3 && $row < 6) && $column < 3) {
            return $this->boxes[1][0];
        }
        if (($row >= 3 && $row < 6) && ($column >= 3 && $column < 6)) {
            return $this->boxes[1][1];
        }
        if (($row >= 3 && $row < 6) && $column >= 6) {
            return $this->boxes[1][2];
        }
        if ($row >= 6 && $column < 3) {
            return $this->boxes[2][0];
        }
        if ($row >= 6 && ($column >= 3 && $column < 6)) {
            return $this->boxes[2][1];
        }
        if ($row >= 6 && $column >= 6) {
            return $this->boxes[2][2];
        }
    }

    function getColumns() {

        $this->columns = array();
        for ($j = 0; $j < 9; $j++) {
            for ($i = 0; $i < 9; $i++) {
                $this->columns[$j][] = $this->solution[$i][$j];
            }
        }
    }

    function getBoxes() {

        $this->boxes = array();
        for ($group1 = 0; $group1 < 3; $group1++) {
            for ($group2 = 0; $group2 < 3; $group2++) {
                $indexs = $this->grouping[$group1];
                $second_index = $this->grouping[$group2];
                foreach ($indexs as $index) {
                    foreach ($second_index as $sindex) {
                        $this->boxes[$group1][$group2][] = $this->solution[$index][$sindex];
                    }
                }
            }
        }
    }

    function setMissingValues() {
        $this->values_to_find = array();
        $this->missing_in_rows = array();
        $this->missing_in_columns = array();


        foreach ($this->solution as $key1 => $row) {
            foreach ($row as $key2 => $column) {
                if ($column == 0) {
                    $this->values_to_find[] = [$key1, $key2];
                    if (!isset($this->missing_in_rows[$key1]))
                        $this->missing_in_rows[$key1] = 1;
                    else
                        $this->missing_in_rows[$key1] ++;


                    $this->missing_in_columns[$key2][] = $key1;
                }
            }
        }
    }

    function setSolutionValue($row, $column, $value, $line = '') {
        if (empty($value) || !is_int($value) || !in_array($value, $this->numbers)) {
            echo "Wrong value buddy on line number $line<br />";
            return;
        }
        if (isset($this->base_value[$row][$column]) && $this->base_value[$row][$column] != 0) {
            echo "<br />Hey, you are wrong here for row $row and column $column<br />";
            return;
        }
        //echo "Okay, putting values for $row -- $column and value is $value called from $line and remaining is " . count($this->values_to_find) . "<br />";
        $this->solution[$row][$column] = $value;
        $this->getColumns();
        $this->getBoxes();
        $this->setMissingValues();
    }

    function calculate() {
        $this->counter++;
        //echo "call : " . $this->counter . "<br />";
        $this->getColumns();
        //echo "First time!!!<br /><pre>";
        //print_r($this->columns[7]);
        $this->getBoxes();
        $this->setMissingValues();
        $this->setSingleValuesRows();
        $this->setSingleValuesColumns();
        $this->setSingleValuesBoxes();


        if (count($this->values_to_find) == 0) {
            echo "Its solved now<br />";
            return;
        }
        foreach ($this->values_to_find as $value) {
            $diff3 = array();
            $r = $value[0];
            $c = $value[1];
            //echo $r . "--" . $c . "<br />";
            $diff1 = array_diff($this->numbers, $this->solution[$r]);
            $diff2 = array_diff($this->numbers, $this->columns[$c]);
            $box1 = array();
            if ($r < 3 && $c < 3) {
                $box1 = $this->boxes[0][0];
            } elseif ($r < 3 && ($c >= 3 && $c < 6)) {
                $box1 = $this->boxes[0][1];
            } elseif ($r < 3 && ($c >= 6)) {
                $box1 = $this->boxes[0][2];
            } elseif (($r >= 3 && $r < 6) && ($c < 3)) {
                $box1 = $this->boxes[1][0];
            } elseif (($r >= 3 && $r < 6) && ($c >= 3 && $c < 6)) {
                $box1 = $this->boxes[1][1];
            } elseif (($r >= 3 && $r < 6) && ($c >= 6)) {
                $box1 = $this->boxes[1][2];
            } elseif ($r >= 6 && ($c < 3)) {
                $box1 = $this->boxes[2][0];
            } elseif ($r >= 6 && ($c >= 3 && $c < 6)) {
                $box1 = $this->boxes[2][1];
            } elseif ($r >= 6 && $c >= 6) {
                $box1 = $this->boxes[2][2];
            }

            $common_numbers = $this->getCommonNumbers($this->solution[$r], $this->columns[$c], $box1);
            if ($common_numbers != false)
                $this->setSolutionValue($r, $c, $common_numbers, __LINE__);
            /*
              if (!empty($box1)) {
              $diff3 = array_diff($this->numbers, $box1);
              }
              echo "<br />";

              print_r($diff1);
              echo "<br />";
              print_r($diff2);
              echo "<br />";
              print_r($diff3);


              $final_diff = $this->getThreeArrayDiff($diff1, $diff2, $diff3);
              //          echo "<br />";
              //        print_r($final_diff);
              //echo "Remaining values: ";
              //var_dump(count($this->values_to_find));
              if (count($final_diff) > 1) {
              //echo "<br />Not solvable<br />";
              } else {
              if (!empty($final_diff)) {
              //$this->setSolutionValue($r, $c, $final_diff[0]);
              //$this->solution[$r][$c] = $final_diff[0];
              $this->setMissingValues();
              echo "<br />Success<br />";
              }
              }
              echo "<hr />"; */
        }
        $this->checkMissingNumberPossibilities();
    }

    function doCalculations() {
        for ($i = 0; $i < 100; $i++) {
            if (count($this->values_to_find) > 0) {
                $this->calculate();
            }
        }
        echo "<h1>Total tries: " . $this->counter . "</h1>";
    }

    public function validateSeries($series) {


        $values = array_count_values($series);

        foreach ($values as $key => $v) {
            if ($v > 1 && $key != 0) {
                echo "This is not valid";
                return false;
            }
        }
        return true;
    }

    function validDateSudoku() {

        $this->getColumns();
        $this->getBoxes();
        if ($this->validateAllColumns() == FALSE) {
            return false;
        }
        if ($this->validateAllRows() == FALSE) {
            return false;
        }
        if ($this->validateAllBoxes() == FALSE) {
            return false;
        }
        return true;
    }

    public function checkMissingNumberPossibilities() {
        $this->checkMissingNumbersColumns();
        $this->checkMissingNumbersRows();
    }

    public function checkMissingNumbersColumns() {
        //echo "Starting checkMissingNumbersColumns<br />";
        for ($i = 0; $i < 9; $i++) {

            $column = $this->columns[$i];
            for ($j = 1; $j <= 9; $j++) {
                if (!in_array($j, $column)) {
                    //echo "number $j, column $i and values <br />";
                    //$this->printa($column);
                    $this->checkIfPossibeInColumn($j, $i);
                }
            }
        }
        //echo "<br />checkMissingNumbersColumns ends <br />";
    }

    public function checkMissingNumbersRows() {
        for ($i = 0; $i < 9; $i++) {
            $row = $this->solution[$i];
            for ($j = 1; $j <= 9; $j++) {
                if (!in_array($j, $row)) {
                    $this->checkIfPossibleInRow($j, $i);
                }
            }
        }
    }

    public function checkIfPossibleInRow($value, $row) {
        $possibles = 0;
        $possible_positions = array();
        for ($i = 0; $i < 9; $i++) {
            $possibility = $this->checkPossibleAtCell($value, array($row, $i));
            if ($possibility) {
                $possibles++;
                $possible_positions[] = array($row, $i);
            }
        }
        if ($possibles == 1) {

            //echo "Number $value is possible at $possibles place in row $row<br />";
            $this->setSolutionValue($row, $possible_positions[0][1], $value, __LINE__);
        }
    }

    public function checkIfPossibeInColumn($value, $column) {
        $possibles = 0;
        $possible_positions = array();
        for ($i = 0; $i < 9; $i++) {
            $possibility = $this->checkPossibleAtCell($value, array($i, $column));
            if ($possibility) {
                $possibles++;
                $possible_positions[] = array($i, $column);
            }
        }
        if ($possibles == 1) {
            //echo "Number $value is possible at $possibles place in column $column<br />";
            //$this->printa($possible_positions);
            $this->setSolutionValue($possible_positions[0][0], $column, $value, __LINE__);
        }
    }

    public function checkPossibleAtCell($value, $position) {

        $row = $position[0];
        $column = $position[1];


        // check base values
        if (isset($this->base_value[$row][$column]) && $this->base_value[$row][$column] != 0) {
            return false;
        }

        // check for existing value
        if (isset($this->solution[$row][$column]) && $this->solution[$row][$column] != 0) {
            return false;
        }
        // check in row
        if (in_array($value, $this->solution[$row])) {
            return false;
        }
        // check column
        if (in_array($value, $this->columns[$column])) {
            return false;
        }


        // check boxes
        $box = $this->getBoxByPosition(array($row, $column));


        if (in_array($value, $box))
            return false;


        return true;
    }

    function printa($array) {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

    function validateAllColumns() {
        for ($i = 0; $i < 9; $i++) {
            if ($this->validateSeries($this->columns[$i]) == FALSE) {
                echo "In columns please<br />";
                return false;
            }
        }
        return true;
    }

    function validateAllRows() {
        for ($i = 0; $i < 9; $i++) {
            if ($this->validateSeries($this->solution[$i]) == FALSE) {
                echo "In rows please<br />";
                return false;
            }
        }
        return true;
    }

    function validateAllBoxes() {
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 3; $j++) {
                $box = $this->boxes[$i][$j];
                if ($this->validateSeries($box) == FALSE) {
                    echo "In Boxes please<br />";
                    return false;
                }
            }
        }
        return true;
    }

    function showSudoku() {
        if ($this->validDateSudoku() == FALSE) {
            echo "Solution is not valid";
        }
        ?>
        <div style="margin-bottom:200px;float: left;">
            <div style="float: left; width:60px; padding-left: 30px;">

                <table border="1" cellpadding="0" cellspacing="0" style="margin-top:40px;">

        <?php for ($i = 0; $i < 9; $i++) { ?>
                        <tr height="50">
                            <td width="20" style="text-align: center;"><?php echo $i; ?></td>
                        </tr>
        <?php } ?>

                </table>
            </div>
            <div style="float:left;">
                <table border="1" cellpadding="0" cellspacing="0">
                    <tr>
                        <?php for ($i = 0; $i < 9; $i++) { ?>
                            <td width="50" style="text-align: center;"><?php echo $i; ?></td>
        <?php } ?>
                    </tr>
                </table>
                <br />
                <table border="1" cellpadding="0" cellspacing="0">

                    <tr height="50">
                        <?php
                        for ($i = 0; $i < 9; $i++) {
                            $row = $i;

                            $border_bottom = '';
                            if ($row == 2 || $row == 5)
                                $border_bottom = 'border-bottom:2px solid #000;';
                            for ($j = 0; $j < 9; $j++) {
                                $column = $j;
                                $border = '';
                                if ($column == 2 || $column == 5)
                                    $border = "border-right:2px solid #000;";

                                $color = '';
                                if ($this->base_value[$i][$j] == 0) {
                                    $color = 'color:red;';
                                }
                                ?>
                                <td width="50" style="text-align: center; <?php echo $border . $border_bottom . $color; ?>">
                                <?php echo ($this->solution[$row][$column] != 0) ? $this->solution[$row][$column] : ''; ?>
                                </td>
                            <?php }
                            ?>
                            <?php if ($i != 8) { ?>
                            </tr><tr height="50">
                            <?php } ?>
                            <?php
                        }
                        ?>
                    </tr>
                </table>
            </div>

        </div>
        <?php
    }

}
