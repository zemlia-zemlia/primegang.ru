<?php
/* @var $this PagesController */
/* @var $data Pages */
?>

<div class="view panel panel-default" style="color:#505050;">
	<div class="panel-heading">	<?php echo CHtml::link(CHtml::encode($data->name), array('update', 'id'=>$data->id)); ?></div>
	<div class="panel-body">
	<b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
	<?php echo CommonFunctions::truncateText($data->text); ?>
	<br />
	</div>
</div>