<?php
/* @var $this GamesController */
/* @var $model Games */

$this->breadcrumbs=array(
	'Games'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'Список Games', 'url'=>array('index')),
	array('label'=>'Создать Games', 'url'=>array('create')),
	array('label'=>'Update Games', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Games', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Games', 'url'=>array('admin')),
);
?>

<h1>View Games #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_league',
		'id_season',
		'id_tour',
		'date',
		'name',
		'alias',
		'text',
		'id_team1',
		'id_team2',
		'score_team1_total',
		'score_team2_total',
	),
)); ?>
