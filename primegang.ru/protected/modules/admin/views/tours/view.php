<?php
/* @var $this ToursController */
/* @var $model Tours */

$this->breadcrumbs=array(
	'Tours'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Список Tours', 'url'=>array('index')),
	array('label'=>'Создать Tours', 'url'=>array('create')),
	array('label'=>'Update Tours', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Tours', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Tours', 'url'=>array('admin')),
);
?>

<h1>View Tours #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_league',
		'id_season',
		'tour_number',
	),
)); ?>
