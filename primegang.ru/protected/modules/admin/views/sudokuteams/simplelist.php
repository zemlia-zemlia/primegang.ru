<?php
/* @var $this SudokuteamsController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
	array('label'=>'Модерация', 'url'=>array('moderation')),
	array('label'=>'Общий список', 'url'=>array('index')),
);
?>

<h1>Команды игроков судоку</h1>

<?php $this -> widget( 'zii.widgets.CListView', array (
        'dataProvider' =>$dataProvider ,
        'itemView' =>'_view' ,
        'id'=>'_flistview',
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
