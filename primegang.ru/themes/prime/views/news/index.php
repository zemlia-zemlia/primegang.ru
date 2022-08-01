<?php
/* @var $this NewsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Новости',
);
$this->pageTitle = "Новости";


?>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
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
		</div>
		<div class="col-lg-6 right">
			<?php $this->widget('Statistics', array(
				'type'	=>"season",
				'dataId'=> "",
				'limit' => 10,
				'view' 	=> "_sidebar",
			)); ?>
		</div>
	</div>
</div>			