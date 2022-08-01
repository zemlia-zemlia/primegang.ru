<?php
/* @var $this SudokuteamsController */
/* @var $model SudokuTeams */

$this->breadcrumbs=array(
	'Sudoku Teams'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать SudokuTeams</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>