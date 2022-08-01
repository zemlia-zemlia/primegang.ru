<?php
$this->breadcrumbs=array(
  'Опросы',
);

$this->menu=array(
  array('label'=>'Создать опрос', 'url'=>array('create')),
);
?>

<h1>Опросы</h1>

<?php $this->widget('zii.widgets.CListView', array(
  'dataProvider'=>$dataProvider,
  'itemView'=>'_view',
)); ?>
