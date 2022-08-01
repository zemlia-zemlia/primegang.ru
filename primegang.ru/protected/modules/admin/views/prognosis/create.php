<?php
/* @var $this PrognosisController */
/* @var $model Prognosis */

$this->breadcrumbs=array(
	'Prognosises'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать Prognosis</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>