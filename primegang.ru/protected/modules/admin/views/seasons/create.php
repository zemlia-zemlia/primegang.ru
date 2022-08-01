<?php
/* @var $this SeasonsController */
/* @var $model Seasons */

$this->breadcrumbs=array(
	'Seasons'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать сезон</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>