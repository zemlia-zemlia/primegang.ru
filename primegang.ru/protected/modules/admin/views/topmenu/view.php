<?php
/* @var $this TopmenuController */
/* @var $model TopMenu */

$this->breadcrumbs=array(
	'Top Menus'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List TopMenu', 'url'=>array('index')),
	array('label'=>'Create TopMenu', 'url'=>array('create')),
	array('label'=>'Update TopMenu', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete TopMenu', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage TopMenu', 'url'=>array('admin')),
);
?>

<h1>View TopMenu #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'url',
	),
)); ?>
