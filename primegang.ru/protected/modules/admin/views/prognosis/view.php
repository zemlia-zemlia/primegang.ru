<?php
/* @var $this PrognosisController */
/* @var $model Prognosis */

$this->breadcrumbs=array(
	'Prognosises'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'Список Prognosis', 'url'=>array('index')),
	array('label'=>'Создать Prognosis', 'url'=>array('create')),
	array('label'=>'Update Prognosis', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Prognosis', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Prognosis', 'url'=>array('admin')),
);
?>

<h1>View Prognosis #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_user',
		'id_game',
		'score_team1_total',
		'score_team2_total',
		'computed',
		'balls',
	),
)); ?>
