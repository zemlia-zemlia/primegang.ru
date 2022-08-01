<?php
/* @var $this ToursController */
/* @var $model Tours */

$this->breadcrumbs=array(
	'Tours'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать тур</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>