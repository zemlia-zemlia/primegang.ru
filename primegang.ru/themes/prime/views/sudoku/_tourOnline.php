<?php 
	//если туры состоялись, все игры отмечены как ready
	$allReady = true;
	foreach($currentTour->games as $game) if(!$game->ready) $allReady = false;
?>
<div class="tour <?php echo ($allReady)?"prev":"next"?>">
	<a class="btn-block tourinfo" data-toggle="collapse" href="#collapse<?php echo $currentTour->id;?>" aria-expanded="false" aria-controls="collapseExample">
		<div class="tour_next_prev"><?php echo ($allReady)?"":"Текущий тур"?></div>
		<div class="tour_date">Дедлайн подачи прогнозов: <b><?php echo $currentTour->date;?></b></div>
		<div class="tour_number"><?php echo $currentTour->name;?></div>					
	</a>
	<div class="collapse tour_results" id="collapse<?php echo $currentTour->id;?>" aria-expanded="false">
		<?php
			$criteria = new CDbCriteria(array(
				'order'	=> 'time, date, id', 'condition' => 'id_tour=:id_tour', 'params' => array('id_tour'=>$currentTour->id),
			));
			$games = SudokuGames::model()->findAll($criteria);
			if(empty($games)):?>
			<div>В данном туре нет матчей!</div>
		<?php else:?>
			<table class="table games">
				<tr>
					<th class="time">Тайм</th>
					<th class="results" colspan="4">Матчи и результаты</th>
					<th class="time">&nbsp;</th>
				</tr>
				<?php foreach($games as $game):?>
				<tr>
					<td class="time"><?php echo $game->time;?></td>
					<td class="team"><?php echo isset($game->team1->name) ? $game->team1->name : "Неизвестная команда";?></td>
					<?php if($game->ready):?>
						<td class="score"><span><?php echo $game->score_team1_total;?></span></td>
						<td class="score"><span><?php echo $game->score_team2_total;?></span></td>
					<?php else:?><td colspan="2">&mdash;</td><?php endif;?>
					<td class="team"><?php echo isset($game->team2->name) ? $game->team2->name : "Неизвестная команда";?></td>
					<td class="time">&nbsp;</td>
				</tr>
				<?php endforeach;?>
			</table>
			<div class="protocol">
				<a href="/sudoku/touronline/<?php echo $currentTour->id;?>">Протокол тура</a>
			</div>
		<?php endif;?>
	</div>
</div>
