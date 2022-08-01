<div class="tour next">
	<a class="btn-block tourinfo" data-toggle="collapse" href="#collapse<?php echo $currentTour->id;?>" aria-expanded="true" aria-controls="collapseExample">
		<div class="tour_next_prev">Следующий тур</div>
		<div class="tour_date">Дедлайн подачи прогнозов: <b><?php echo $currentTour->date;?></b></div>
		<div class="tour_number"><?php echo $currentTour->name;?></div>					
	</a>
	<div class="collapse tour_results in" id="collapse<?php echo $currentTour->id;?>" aria-expanded="true">
		<?php if(Yii::app()->user->hasFlash('prognosis')): ?>
		<div class="flash-success">
			<?php echo Yii::app()->user->getFlash('prognosis'); ?>
		</div>
		<?php endif; ?>
		
		<?php
			$currentPrognosis->load($currentTour->id, $sudokuPlayer->id);
			if(!empty($currentPrognosis->_lines)):
			$form = $this->beginWidget('CActiveForm', array(
				'id'=>'prognosis-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
			));									
		?>
			<?php echo $form->hiddenField($currentPrognosis,"id_tour");?>
			<?php echo $form->hiddenField($currentPrognosis,"id_player");?>
			<div class="time time1">
				<h3>Тайм 1</h3>
				<?php foreach($currentPrognosis->_lines[1] as $line):?>
				<div class="line editable game">
					<div class="pull-right team team2"><?php echo (isset($line->game->team2->name))?$line->game->team2->name:"?";?></div>
					<div class="pull-left team team1"><?php echo (isset($line->game->team1->name))?$line->game->team1->name:"?";;?></div>
					<div class="prognosis score">
						<?php if(strtotime($currentTour->date) >= time()):?>
							<span><input type="text" name="SudokuTourPrognosis[lines][1][<?php echo $line->game->id;?>][p1]" value="<?php echo $line->prognosis->p1;?>"></span>
							<span><input type="text" name="SudokuTourPrognosis[lines][1][<?php echo $line->game->id;?>][p2]" value="<?php echo $line->prognosis->p2;?>"></span>
							<span><input type="text" name="SudokuTourPrognosis[lines][1][<?php echo $line->game->id;?>][p3]" value="<?php echo $line->prognosis->p3;?>"></span>
						<?php else:?>
							<span class="glyphicon glyphicon-remove"></span>
						<?php endif;?>
					</div>
				</div>
				<?php endforeach;?>
			</div>
			<div class="time time2">
				<h3>Тайм 2</h3>
				<?php foreach($currentPrognosis->_lines[2] as $line):?>
				<div class="line editable game">
					<div class="pull-right team team2"><?php echo (isset($line->game->team2->name))?$line->game->team2->name:"?";?></div>
					<div class="pull-left team team1"><?php echo (isset($line->game->team1->name))?$line->game->team1->name:"?";;?></div>
					<div class="prognosis score">
						<?php if(strtotime($currentTour->date) >= time()):?>
							<span><input type="text" name="SudokuTourPrognosis[lines][2][<?php echo $line->game->id;?>][p1]" value="<?php echo $line->prognosis->p1;?>"></span>
							<span><input type="text" name="SudokuTourPrognosis[lines][2][<?php echo $line->game->id;?>][p2]" value="<?php echo $line->prognosis->p2;?>"></span>
							<span><input type="text" name="SudokuTourPrognosis[lines][2][<?php echo $line->game->id;?>][p3]" value="<?php echo $line->prognosis->p3;?>"></span>
						<?php else:?>
							<span class="glyphicon glyphicon-remove"></span>
						<?php endif;?>
					</div>
				</div>
				<?php endforeach;?>
			</div>
			<div class="buttons">
				<button type="submit" class="btn btn-primary">Отправить</button>
			</div>
			<?php echo $form->errorSummary($currentPrognosis);?>									
			<?php $this->endWidget(); ?>
			
			<!-- команды, которые отправили состав -->
			<div class="readyTeams">
				<div class="header"><h6>Отправленные составы</h6></div>
				<div class="items">
					<?php
						$readyTeams = $currentTour->getReadyTeams();
						foreach($readyTeams as $t):
					?>
						<div class="item"><?php echo $t['name_team'];?></div>
					<?php endforeach;?>
				</div>
			</div>
		<?php else:?>
			<p>В этом туре еще нет матчей!</p>
		<?php endif;?>
	</div>
</div>