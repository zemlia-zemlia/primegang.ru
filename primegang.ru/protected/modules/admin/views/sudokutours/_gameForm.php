<tr>
	<td>
		<input type="hidden" name="SudokuTours[_games][<?php echo $i;?>][id]" value="<?php echo $game->id;?>">
		<input type="hidden" name="SudokuTours[_games][<?php echo $i;?>][id_season]" value="<?php echo $game->id_season;?>">
		<input type="hidden" name="SudokuTours[_games][<?php echo $i;?>][id_tour]" value="<?php echo $game->id_tour;?>">
		<?php if($game->isNewRecord):?>
			<input type="hidden" name="SudokuTours[_games][<?php echo $i;?>][time]" value="<?php echo ($i < 3)?"1":"2";?>">
			<?php echo ($i < 3)?"1":"2"; ?>
		<?php else:?>
			<input type="hidden" name="SudokuTours[_games][<?php echo $i;?>][time]" value="<?php echo $game->time;?>">
			<?php echo $game->time; ?>
		<?php endif;?>
	</td>
	<td>
		<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
		    $this->widget('CJuiDateTimePicker',array(
		        'model'=>$game, //Model object
		        'attribute'=>'date', //attribute name
		        'mode'=>'datetime', //use "time","date" or "datetime" (default)
		        'htmlOptions'=>array('class'=>'form-control', 'name'=>'SudokuTours[_games]['.$i.'][date]','id'=>'SudokuTours__games_'.$i.'_date'),
		        'language'=>'ru',
		        'options'=>array('showAnim' => 'fold') // jquery plugin options
		    ));
		?>					
		<?php echo $form->error($game,'date'); ?>
	</td>
	<td>
		<?php echo $form->hiddenField($game,'id_team1',array(
			'data-column'=>'SudokuTours__games_'.$i.'_id_team1',
			'name'=>'SudokuTours[_games]['.$i.'][id_team1]',			
		)); ?>
		<button class="btn btn-default btn-block select" id="id_team1_select" type="button"
			data-href="/admin/teams/select"
			data-column="SudokuTours__games_<?php echo $i;?>_id_team1"><?php
			if(empty($game->team1)) echo "Выберите значение";
			else echo $game->team1->name; 
		?></button>
		<?php echo $form->error($game,		'id_team1'); ?>
	</td>
	<td>
		<?php echo $form->textField($game,	'score_team1_total',array('class'=>'form-control','data-column'=>'score_team1_total', 'name'=>'SudokuTours[_games]['.$i.'][score_team1_total]')); ?>
		<?php echo $form->error($game,		'score_team1_total'); ?>
	</td>
	<td>
		<?php echo $form->textField($game,	'score_team2_total',array('class'=>'form-control','data-column'=>'score_team2_total', 'name'=>'SudokuTours[_games]['.$i.'][score_team2_total]')); ?>
		<?php echo $form->error($game,		'score_team2_total'); ?>
	</td>
	<td>
		<?php echo $form->hiddenField($game,'id_team2',array(
			'data-column'=>'SudokuTours__games_'.$i.'_id_team2',
			'name'=>'SudokuTours[_games]['.$i.'][id_team2]',			
		)); ?>
		<button class="btn btn-default btn-block select" id="id_team2_select" type="button"
			data-href="/admin/teams/select"
			data-column="SudokuTours__games_<?php echo $i;?>_id_team2"><?php
			if(empty($game->team2)) echo "Выберите значение";
			else echo $game->team2->name; 
		?></button>
		<?php echo $form->error($game,		'id_team2'); ?>
	</td>
	<td><?php echo $form->checkBox($game,	'ready',array('class'=>'form-control','data-column'=>'ready', 'name'=>'SudokuTours[_games]['.$i.'][ready]')); ?></td>
</tr>