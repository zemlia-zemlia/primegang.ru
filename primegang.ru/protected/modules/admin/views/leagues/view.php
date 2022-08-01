<?php
/* @var $this LeaguesController */
/* @var $model Leagues */

$this->breadcrumbs=array(
	'Leagues'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список Leagues', 'url'=>array('index')),
	array('label'=>'Создать Leagues', 'url'=>array('create')),
	array('label'=>'Update Leagues', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Leagues', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Leagues', 'url'=>array('admin')),
);
?>

<h1>View Leagues #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'alias',
	),
)); ?>
