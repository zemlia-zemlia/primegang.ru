<script>
	$(function() {
		//расставим selected, если в .player есть input[type=hidden].ph и все c value=1
		$(".sudokuTime .players .player").each(function(index, element) {
			var _playing = $(element).find("input.ph[type=hidden][value='1']");
			if(_playing.length == 3) $(element).addClass('selected');
		});
	});
</script>


<div style="margin-bottom: 20px;">
	Название команды: <b><?php echo $sudokuPlayer->team->name;?></b>
</div>
<table class="table">
	<tr>
		<th>Должность</th>
		<th>Имя</th>
		<th>Никнейм</th>
	</tr>
<?php foreach($sudokuPlayer->team->players as $player):?>
	<tr>
		<td><?php if($player->captain):?>Капитан<?php elseif($player->vicecaptain):?>Вице-капитан<?php else:?>Игрок<?php endif;?></td>
		<td><?php echo $player->name;?></td>
		<td><?php echo $player->user->display_name;?></td>
	</tr>
<?php endforeach; ?>
</table>

<?php
	$currentSudokuTour = SudokuTours::currentTour();
	if(!empty($currentSudokuTour) && $sudokuPlayer->team->active):
		$teamPlays = false;
		$tourTeams = SudokuToursTeams::model()->find(
			"id_tour=:id_tour and (id_sudoku_team1=:id_team or id_sudoku_team2=:id_team)", 
			array("id_tour"=>$currentSudokuTour->id, "id_team"=>$sudokuPlayer->team->id)
		);
		if(!empty($tourTeams)) $teamPlays = true;		  
?>
	<?php if($teamPlays):?>
		<div class="sudokuTour">
			<!--назначение прогнозов по текущему туру судоку-->
			<h2>Текущий тур по судоку: <?php echo $currentSudokuTour->name; ?></h2>
			<div class="deadline">Дедлайн первого тайма: <b><?php echo $currentSudokuTour->date; ?></b></div>
			<div class="deadline">Дедлайн второго тайма: <b><?php echo $currentSudokuTour->date_cap; ?></b></div>
			<p class="hint">Игроки имеют право подавать прогнозы только до первого дедлайна. Капитаны имеют право менять состав на второй тайм до дедлайна второго тайма.</p>
			
			<?php
				$availableTime1 = false;
				$availableTime2 = false;
				$avT1 = $availableTime1;
				$avT2 = $availableTime2;
			?>
			
			<div class="time1 sudokuTime">
				<h3>Первый тайм</h3>
				<div class="games cblock">
					<?php foreach($currentSudokuTour->games as $game):
						if($game->time <> 1) continue;?>
						<div class="game"><b><?php echo $game->date;?></b> <?php echo $game->team1->name;?> &mdash; <?php echo $game->team2->name;?></div>
					<?php endforeach;?>
				</div>
				<div class="lineup">
					<h3>Состав на первый тайм</h3>
					<div class="players cblock <?php if($avT1) echo "selectable";?>">
						<?php foreach($sudokuPlayer->team->players as $pl):?>
							<?php if($avT1):?><a class="player" href="#"><?php else:?><div class="player"><?php endif;?>
								<div class="prognosis">
									<?php foreach($currentSudokuTour->games as $game):
										if($game->time <> 1) continue;
										$pr = SudokuPrognosis::model()->find(
											'id_player=:id_player and id_game=:id_game',
											array('id_player'=>$pl->id,'id_game'=>$game->id)
										);
										if(!empty($pr)):?>
											<div class="row">
												<input type="hidden" class="ph" name="PlayingPrognosis[<?php echo $game->id;?>][<?php echo $pr->id;?>]" value="<?php echo $pr->playing;?>">
												<span><?php echo $pr->p1;?></span>
												<span><?php echo $pr->p2;?></span>
												<span><?php echo $pr->p3;?></span>
											</div>
										<?php else: ?>
											<div class="row empty">-</div>
										<?php endif; ?>
									<?php endforeach;?>
								</div>
								<div class="name"><?php echo $pl->name.' <i>('.$pl->user->display_name.')</i>';?></div>
							<?php if($avT1):?></a><?php else:?></div><?php endif;?>
						<?php endforeach;?>
					</div>
				</div>
			</div>
		
			<div class="time2 sudokuTime">
				<h3>Второй тайм</h3>
				<div class="games cblock">
					<?php foreach($currentSudokuTour->games as $game):
						if($game->time <> 2) continue;?>
						<div class="game"><b><?php echo $game->date;?></b> <?php echo $game->team1->name;?> &mdash; <?php echo $game->team2->name;?></div>
					<?php endforeach;?>
				</div>
				<div class="lineup">
					<h3>Состав на второй тайм</h3>
					<div class="players cblock <?php if($avT2) echo "selectable";?>">
						<?php foreach($sudokuPlayer->team->players as $pl):?>
							<?php if($avT2):?><a class="player" href="#"><?php else:?><div class="player"><?php endif;?>
								<div class="prognosis">
									<?php foreach($currentSudokuTour->games as $game):
										if($game->time <> 2) continue;
										$pr = SudokuPrognosis::model()->find(
											'id_player=:id_player and id_game=:id_game',
											array('id_player'=>$pl->id,'id_game'=>$game->id)
										);
										if(!empty($pr)):?>
											<div class="row">
												<input type="hidden" class="ph" name="PlayingPrognosis[<?php echo $game->id;?>][<?php echo $pr->id;?>]" value="<?php echo $pr->playing;?>">
												<span><?php echo $pr->p1;?></span>
												<span><?php echo $pr->p2;?></span>
												<span><?php echo $pr->p3;?></span>
											</div>
										<?php else: ?>
											<div class="row empty">-</div>
										<?php endif; ?>
									<?php endforeach;?>
								</div>
								<div class="name"><?php echo $pl->name.' <i>('.$pl->user->display_name.')</i>';?></div>
							<?php if($avT2):?></a><?php else:?></div><?php endif;?>
						<?php endforeach;?>
					</div>
				</div>
			</div>
		</div>
	<?php else:?>
		Ваша команда не играет в текущем туре VIVA SUDOKU!
	<?php endif;?>
<?php endif;?>