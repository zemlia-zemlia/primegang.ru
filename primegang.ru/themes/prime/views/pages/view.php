<?php
/* @var $this PagesController */
/* @var $model Pages */

$this->breadcrumbs=array(
	'Pages'=>array('index'),
	$model->name,
);
$this->pageTitle = $model->name;

?>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 single page">
			<h1><?php echo $model->name;?></h1>
			<div class="text"><?php
				echo $model->text; 
			?></div>
		</div>			
		<!--<div class="col-lg-6 right">
			<div class="league_sidebar">
				<?php $this->widget('Statistics', array(
					'type'	=>"season",
					'dataId'=> "",
					'limit' => 10,
					'view' 	=> "_sidebar",
				)); ?>
			</div>
		</div>-->
	</div>
</div>
