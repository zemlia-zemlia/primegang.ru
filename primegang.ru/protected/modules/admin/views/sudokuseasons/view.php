<?php
/* @var $this SeasonsController */
/* @var $model Seasons */

$this->breadcrumbs=array(
	'Seasons'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список Seasons', 'url'=>array('index')),
	array('label'=>'Создать Seasons', 'url'=>array('create')),
	array('label'=>'Update Seasons', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Seasons', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Seasons', 'url'=>array('admin')),
);
?>

<h1>View Seasons #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'alias',
	),
)); ?>
