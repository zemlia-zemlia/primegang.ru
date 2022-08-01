<?php
/* @var $this LeaguesController */
/* @var $model Leagues */

$this->breadcrumbs=array(
	'Leagues'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать лигу</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>