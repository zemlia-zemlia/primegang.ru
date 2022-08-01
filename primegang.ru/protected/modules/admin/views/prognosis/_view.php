<?php
/* @var $this PrognosisController */
/* @var $data Prognosis */
?>


<div class="view panel panel-default">
	<div class="panel-body">
					<b><?php echo CHtml::encode($data->getAttributeLabel('id_user')); ?>:</b>
	<?php
						if(!empty($data->user)) echo $data->user->username;
						else echo ""; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('id_game')); ?>:</b>
	<?php
						if(!empty($data->game)) echo $data->game->name;
						else echo ""; ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score_team1_total')); ?>:</b>
	<?php echo CHtml::encode($data->score_team1_total); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score_team2_total')); ?>:</b>
	<?php echo CHtml::encode($data->score_team2_total); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('computed')); ?>:</b>
	<?php echo CHtml::encode($data->computed); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('balls')); ?>:</b>
	<?php echo CHtml::encode($data->balls); ?>
	<br />

	
	</div>
</div>