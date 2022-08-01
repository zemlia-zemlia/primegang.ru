<?php
/* @var $this SudokutoursController */
/* @var $model SudokuTours */

$this->breadcrumbs=array(
	'Sudoku Tours'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Список SudokuTours', 'url'=>array('index')),
	array('label'=>'Создать SudokuTours', 'url'=>array('create')),
	array('label'=>'Update SudokuTours', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SudokuTours', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SudokuTours', 'url'=>array('admin')),
);
?>

<h1>View SudokuTours #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_season',
		'tour_number',
		'date',
	),
)); ?>
