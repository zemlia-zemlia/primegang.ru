<?php
/* @var $this ToursController */
/* @var $model Tours */

$this->breadcrumbs=array(
	'Tours'=>array('index'),
	$model->id=>array('update','id'=>$model->id),
	'Редактировать',
);

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Создать', 'url'=>array('create')),
	array('label'=>'Удалить', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Вы уверены, что хотите удалить этот элемент?')),
	
);
?>

<h1><?php echo $model->name; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>