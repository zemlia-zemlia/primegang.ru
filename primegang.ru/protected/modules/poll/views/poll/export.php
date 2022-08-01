<?php
$this->breadcrumbs=array(
  'Опросы'=>array('index'),
  $model->title,
);

$this->menu=array(
  array('label'=>'Список опросов', 'url'=>array('index')),
  array('label'=>'Создать опрос', 'url'=>array('create')),
  array('label'=>'Просмотреть опрос', 'url'=>array('view', 'id'=>$model->id)),
  array('label'=>'Редактировать опрос', 'url'=>array('update', 'id'=>$model->id)),
  array('label'=>'Удалить опрос', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Хотите удалить этот опрос?')),
);
?>

<h1>Экспорт <em><?php echo CHtml::encode($model->title); ?></em></h1>

<div class="form">
  <?php echo $cform->render(); ?>
</div>
