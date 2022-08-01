<?php
/* @var $this TopmenuController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Top Menus',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);
?>

<h1>Top Menus</h1>

<?php $this -> widget( 'zii.widgets.CListView', array (
        'dataProvider' =>$dataProvider ,
        'itemView' =>'_view' ,
        'template' =>"{items} \n <nav>{pager}</nav>" ,
        'pagerCssClass' => 'cpager',
        'pager' => array (
       'firstPageLabel' =>'&laquo;' ,
       'prevPageLabel' =>'&lt;' ,
       'nextPageLabel' =>'&gt;' ,
       'lastPageLabel' =>'&raquo;' ,
       'selectedPageCssClass' => 'active',
       'maxButtonCount' =>'5' ,
       'header' =>'' ,
       'htmlOptions' =>array ( 'class'=> 'pagination' )
   ),                
)); ?>
