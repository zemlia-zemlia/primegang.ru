<?php
/* @var $this SudokuteamsController */
/* @var $data SudokuTeams */
?>


<div class="view panel panel-default">
	<div class="panel-heading">	<?php echo CHtml::link(CHtml::encode($data->name), array('update', 'id'=>$data->id)); ?></div>
	<div class="panel-body">
		<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
		<?php echo CHtml::encode($data->name); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('alias')); ?>:</b>
		<?php echo CHtml::encode($data->alias); ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('active')); ?>:</b>
		<?php echo ($data->active)?"Да":"Нет"; ?>
		<br />

		<b><?php echo CHtml::encode($data->getAttributeLabel('comment')); ?>:</b>
		<?php echo CHtml::encode($data->comment); ?>
		<br />

	
	</div>
</div>