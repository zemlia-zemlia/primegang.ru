<?php
/* @var $this ToursController */
/* @var $data Tours */
?>

<div class="view panel panel-default">
	<div class="panel-heading">	<?php echo CHtml::link(CHtml::encode($data->id), array('update', 'id'=>$data->id)); ?></div>
	<div class="panel-body">
			<b><?php echo CHtml::encode($data->getAttributeLabel('id_league')); ?>:</b>
	<?php echo CHtml::encode($data->id_league); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_season')); ?>:</b>
	<?php echo CHtml::encode($data->id_season); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tour_number')); ?>:</b>
	<?php echo CHtml::encode($data->tour_number); ?>
	<br />

	
	</div>
</div>