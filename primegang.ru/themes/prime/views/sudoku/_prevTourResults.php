<?php
	$criteria = new CDbCriteria();
	$criteria->order = "tour_number DESC";

	$params = array();
	if($hasCurrentTour) {
	    $criteria->addCondition("id <> :current_id");
	    $params['current_id'] = $currentTour->id;
	}
	$criteria->addCondition('id_season = :season');
    $params['season'] = $season->id;

    $criteria->params = $params;

	$sudokuTours = SudokuTours::model()->findAll($criteria);
	foreach($sudokuTours as $tour):
?>
	<?php $this->renderPartial('_tourOnline',array('currentTour'=>$tour));?>
	<div class="tour next" style="display:none;">
		<a class="btn-block tourinfo" data-toggle="collapse" href="#collapse<?php echo $tour->id;?>" aria-expanded="false" aria-controls="collapseExample">
			<div class="tour_next_prev">&nbsp;</div>
			<div class="tour_date">&nbsp;</div>
			<div class="tour_number"><?php echo $tour->name;?></div>					
		</a>
		<div class="collapse tour_results" id="collapse<?php echo $tour->id;?>" aria-expanded="false">
			<h3>Исходы игр</h3>
			<div>
			<?php
				$crit = new CDbCriteria();
				$crit->order	 = "time, date";
				$crit->condition = "id_tour=:id_tour and ready=:ready";
				$crit->params	 = array("id_tour"=>$tour->id, 'ready'=>1);
				$tourGames = SudokuGames::model()->findAll($crit); 
				if(empty($tourGames)):
			?>
				Результаты игр еще не внесены!
			<?php else:?>
				<?php foreach($tourGames as $game):?>
					<?php echo $game->name;?>&nbsp;<?php echo $game->score_team1_total;?> : <?php echo $game->score_team2_total;?><br/>
				<?php endforeach;?>
			<?php endif;?>
			</div>
			
			<h3>Счета команд</h3>
			<div>
			<?php
				$tourTeams = SudokuToursTeams::model()->findAll("id_tour=:id_tour",array('id_tour'=>$tour->id));
				if(empty($tourTeams)):
			?>
				<p>В этом туре никто не играл :(</p>
			<?php else:?>
				<?php foreach($tourTeams as $tt):?>
				<?php echo $tt->name;?>&nbsp;<?php if($tt->computed): 
					echo $tt->score_team1_total;
					echo " - ";
					echo $tt->score_team2_total; 
				endif;?><br/>
				<?php endforeach;?>
			<?php endif;?>
			</div>
			
			<h3>Баллы игроков</h3>
			<div>
				<?php
					$stats = $tour->getUserTourStats();
					if(empty($stats)): 
				?>
				Нет прогнозистов.
				<?php else:?>
					<?php foreach($stats as $stat):?>
					<?php echo $stat['name_player'];?> <?php echo $stat['points'];?><br/>
					<?php endforeach;?>
				<?php endif;?>
			</div>
			
		</div>
	</div>

<?php endforeach;?>
