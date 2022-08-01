<?php
/* @var $this ToursController */
/* @var $data Tours */
?>


<div class="view panel panel-default">
	<div class="panel-heading">	<?php echo CHtml::link(CHtml::encode($data->name), array('update', 'id'=>$data->id)); ?></div>
	<div class="panel-body">
					<b><?php echo CHtml::encode($data->getAttributeLabel('id_league')); ?>:</b>
	<?php
						if(!empty($data->league)) echo $data->league->name;
						else echo ""; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_season')); ?>:</b>
	<?php
						if(!empty($data->season)) echo $data->season->name;
						else echo ""; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tour_number')); ?>:</b>
	<?php echo CHtml::encode($data->tour_number); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	
	</div>
</div>