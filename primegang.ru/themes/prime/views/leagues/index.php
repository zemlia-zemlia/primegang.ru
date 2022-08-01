<?php
/* @var $this LeaguesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Leagues',
);


?>

<h1>Лиги</h1>

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
       'maxButtonCount' =>'8' ,
       'header' =>'' ,
       'htmlOptions' =>array ( 'class'=> 'pagination' )
   ),                
)); ?>
