<?php
/* @var $this SudokutoursController */
/* @var $data SudokuTours */
?>


<div class="view panel panel-default">
	<div class="panel-heading">	<?php echo CHtml::link(CHtml::encode($data->name), array('update', 'id'=>$data->id)); ?></div>
	<div class="panel-body">
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('date_cap')); ?>:</b>
	<?php echo CHtml::encode($data->date_cap); ?>
	<br />
	
	</div>
</div>