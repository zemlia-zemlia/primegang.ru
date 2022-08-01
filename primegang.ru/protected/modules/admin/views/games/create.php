<?php
/* @var $this GamesController */
/* @var $model Games */

$this->breadcrumbs=array(
	'Games'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать игру</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>