<?php
/* @var $this NewsController */
/* @var $data News */
?>

<div class="view panel panel-default">
	<div class="panel-heading">	<?php echo CHtml::link(CHtml::encode($data->name), array('update', 'id'=>$data->id)); ?></div>
	<div class="panel-body">
						<div class="image_preview"><img src="<?php echo $data->image_url;?>" class="img-rounded"></div>
						<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alias')); ?>:</b>
	<?php echo CHtml::encode($data->alias); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
	<?php echo CommonFunctions::truncateText($data->text); ?>
	<br />

	
	</div>
</div>