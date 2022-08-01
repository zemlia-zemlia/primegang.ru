<?php
$this->breadcrumbs=array(
  'Опросы'=>array('index'),
  $model->title=>array('view','id'=>$model->id),
);

$this->menu=array(
  array('label'=>'Список опросов', 'url'=>array('index')),
  array('label'=>'Создать опрос', 'url'=>array('create')),
  array('label'=>'Редактировать опрос', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Экспорт', 'url'=>array('export', 'id'=>$model->id)),
  array('label'=>'Удалить опрос', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Хотите удалить этот опрос?')),
);
?>

<h1><?php echo CHtml::encode($model->title) ?></h1>

<?php echo $this->renderPartial('_vote', array('model'=>$model,'vote'=>$vote,'choices'=>$choices)); ?>
