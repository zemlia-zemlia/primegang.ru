<?php
/* @var $this SPhotostreamAlbumsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Фотографии',
);

$this->menu=array(
	array('label'=>'Создать', 'url'=>array('create')),
);
?>
<script>
$('.selectable a').click(function(){
	$('#selectModal').modal('hide');
	
	_data = {
		'model':'News',
		'id_model':$(this).find('input[type=hidden][name=item_id]').val(),
		'title':$(this).find('span.name').html(),
	};
	select_callback(_data);
	
	return false;
});
</script>

<?php $this -> widget( 'zii.widgets.CListView', array (
        'dataProvider' =>$dataProvider ,
        'itemView' =>'_selectview' ,
        'template' =>"<div class=\"list-group selectable\">{items}</div> \n <nav>{pager}</nav>" ,
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