<?php
/* @var $this SudokuteamsController */
/* @var $model SudokuTeams */

$this->breadcrumbs=array(
	'Sudoku Teams'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список SudokuTeams', 'url'=>array('index')),
	array('label'=>'Создать SudokuTeams', 'url'=>array('create')),
	array('label'=>'Update SudokuTeams', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete SudokuTeams', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage SudokuTeams', 'url'=>array('admin')),
);
?>

<h1>View SudokuTeams #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'alias',
		'active',
		'comment',
	),
)); ?>
