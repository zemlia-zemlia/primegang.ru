<?php
/* @var $this SudokuteamsController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
	array('label'=>'Модерация', 'url'=>array('moderation')),
	array('label'=>'Играющие', 'url'=>array('active')),
	array('label'=>'Общий список', 'url'=>array('index')),
);
?>

<script>
	var updateList = function() {
	    $.fn.yiiListView.update('_flistview', { 
	        data: $('.search-form form').serialize()
	    });
	}
	$(function(){
		$('.search-form form').submit(function() {
			updateList();
		    return false;
		});
	});
</script>

<button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#search" aria-expanded="false" aria-controls="search">
	Фильтр списка
</button>
<div class="collapse" id="search">
	<div class="well">
		<?php $this->renderPartial('_search',array(
		    'model'=>$model,
		)); ?>
	</div>
</div>


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
