<?php

class ComputeCommand extends CConsoleCommand {
    public function run($args) {
    	echo "hello computer!";
    	PrognoseFunctions::computePrognosis();
		echo "simple computed!";
    	PrognoseFunctions::computeSudokuPrognosis();
		echo "sudoku computed!";
    }
}

?>