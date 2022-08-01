<?php
/* @var $this SudokutoursController */
/* @var $model SudokuTours */

$this->breadcrumbs=array(
	'Sudoku Tours'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать тур</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>