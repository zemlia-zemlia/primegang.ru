<?php
/* @var $this TopmenuController */
/* @var $model TopMenu */

$this->breadcrumbs=array(
	'Top Menus'=>array('index'),
	'Создать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
);
?>

<h1>Создать TopMenu</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>