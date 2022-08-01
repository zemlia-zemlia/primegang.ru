<?php
/* @var $this TeamsController */
/* @var $model Teams */

$this->breadcrumbs=array(
	'Teams'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список Teams', 'url'=>array('index')),
	array('label'=>'Создать Teams', 'url'=>array('create')),
	array('label'=>'Update Teams', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Teams', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Teams', 'url'=>array('admin')),
);
?>

<h1>View Teams #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_league',
		'name',
		'alias',
		'town',
		'image_url',
		'text',
	),
)); ?>
