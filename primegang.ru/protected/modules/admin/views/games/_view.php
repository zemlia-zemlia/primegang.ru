<?php
/* @var $this GamesController */
/* @var $data Games */
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

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_tour')); ?>:</b>
	<?php
						if(!empty($data->tour)) echo $data->tour->name;
						else echo ""; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('date')); ?>:</b>
	<?php echo CHtml::encode($data->date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('name')); ?>:</b>
	<?php echo CHtml::encode($data->name); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alias')); ?>:</b>
	<?php echo CHtml::encode($data->alias); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
	<?php echo CommonFunctions::truncateText($data->text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_team1')); ?>:</b>
	<?php
						if(!empty($data->team1)) echo $data->team1->name;
						else echo ""; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_team2')); ?>:</b>
	<?php
						if(!empty($data->team2)) echo $data->team2->name;
						else echo ""; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score_team1_total')); ?>:</b>
	<?php echo CHtml::encode($data->score_team1_total); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score_team2_total')); ?>:</b>
	<?php echo CHtml::encode($data->score_team2_total); ?>
	<br />

	*/ ?>
	
	</div>
</div>