<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<h2>Ошибка <?php echo $code; ?></h2>
			
			<div class="error">
			<?php echo CHtml::encode($message); ?>
			</div>
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


