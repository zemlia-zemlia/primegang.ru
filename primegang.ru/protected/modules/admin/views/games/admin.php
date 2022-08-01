<?php
/* @var $this GamesController */
/* @var $model Games */

$this->breadcrumbs=array(
	'Games'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Games', 'url'=>array('index')),
	array('label'=>'Create Games', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#games-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Games</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'games-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'id_league',
		'id_season',
		'id_tour',
		'date',
		'name',
		/*
		'alias',
		'text',
		'id_team1',
		'id_team2',
		'score_team1_total',
		'score_team2_total',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
