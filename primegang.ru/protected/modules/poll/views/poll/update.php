<?php
$this->breadcrumbs=array(
  'Опросы'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
  'Редактировать',
);

$this->menu=array(
  array('label'=>'Список опросов', 'url'=>array('index')),
  array('label'=>'Создать опрос', 'url'=>array('create')),
  array('label'=>'Просмотр', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Экспорт', 'url'=>array('export', 'id'=>$model->id)),
);
?>

<h1><?php echo CHtml::encode($model->title); ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'choices'=>$choices)); ?>
