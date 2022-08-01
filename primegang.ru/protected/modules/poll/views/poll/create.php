<?php
$this->breadcrumbs=array(
  'Опросы'=>array('index'),
  'Создать',
);

$this->menu=array(
  array('label'=>'Список опросов', 'url'=>array('index')),
);
?>

<h1>Создать опрос</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'choices'=>$choices)); ?>
