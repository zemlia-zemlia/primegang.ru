<?php
/* @var $this NewsController */
/* @var $model News */

$this->breadcrumbs=array(
	'News'=>array('index'),
	$model->name,
);
$this->pageTitle = $model->name;

?>
<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="news list page">
				<div class="datetags">
					<div class="date"><?php echo CHtml::encode($model->date); ?></div>
				</div>
				<div class="img"><a href="/news/<?php echo $model->id; ?>-<?php echo $model->alias; ?>"><img src="<?php echo $model->image_url;?>"></a></div>
				<h1><?php echo $model->name;?></h1>
				<div class="text"><?php
					echo $model->text; 
				?></div>
			</div>
		</div>			
		<div class="col-lg-6 right">
			<?php $this->widget('Statistics', array(
				'type'	=>"season",
				'dataId'=> "",
				'limit' => 3,
				'view' 	=> "_sidebar",
			)); ?>
		</div>
	</div>
</div>
