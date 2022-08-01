<?php
	$this->pageTitle = "VIVA SUDOKU! — ".$model->name." (протокол)";

?>
<style>
	.games {padding:10px 2px; margin:30px 0;}
	.games h4 {margin-bottom:10px; font-size:14px; text-transform:uppercase; font-weight:normal;}
	.pair_name {width:400px !important;}
	
	
	
</style>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="league touronline">
				<h1 class="league_title"><span class="sudoku"></span>VIVA SUDOKU!</h1>
				<img src="/images/site/league_logo_sudoku.png">
				<div class="tours sudoku">
					<div class="tour next">
						<a class="btn-block tourinfo" data-toggle="collapse" aria-expanded="true" aria-controls="collapseExample">
							<div class="tour_next_prev">Текущий тур</div>
							<div class="tour_date">Дедлайн подачи прогнозов: <b><?php echo $model->date;?></b></div>
							<div class="tour_number"><?php echo $model->name;?></div>					
						</a>

						<div class="tour_results collapse in">
					<?php
						$criteria = new CDbCriteria(array(
							'order'	=> 'time, date, id', 'condition' => 'id_tour=:id_tour', 'params' => array('id_tour'=>$model->id),
						));
						$games = SudokuGames::model()->findAll($criteria);
						if(empty($games)):?>
						<div>В данном туре нет матчей!</div>
					<?php else:?>
						<table class="table games">
							<tr>
								<th colspan="6">Матчи и результаты</th>
								
							</tr>
							<?php foreach($games as $game):?>
							<tr>
								<td class="time"><?php echo $game->time;?></td>
								<td class="team1"><?php echo $game->team1->name;?></td>
								<?php if($game->ready):?>
									<td class="score"><span><?php echo $game->score_team1_total;?></span></td>
									<td class="score"><span><?php echo $game->score_team2_total;?></span></td>								
								<?php else:?><td colspan="2">&mdash;</td><?php endif;?>
								<td class="team2"><?php echo $game->team2->name;?></td>
								
							</tr>
							<?php endforeach;?>
						</table>
					<?php endif;?>
				</div>

				</div>
				</div>
				
				<?php
					if(empty($model->teams)): 
				?>В данном туре никакие команды не участвуют!<?php else:?>
					<?php foreach($model->teams as $pair):?>
						<span style="display:none;"><?php $statistics = $pair->statistics(); ?></span>
						<div class="tour prev">
							<a class="btn-block tourinfo" data-toggle="collapse" href="#collapse<?php echo $pair->id;?>" aria-expanded="false" aria-controls="collapseExample">
								<div class="tour_number pair_name"><?php echo $pair->name;?></div>					
							</a>
							<div class="collapse" id="collapse<?php echo $pair->id;?>"  aria-expanded="false">
								<!--счета команд-->
								<?php if(isset($statistics->time1) || isset($statistics->time2)):?>
									<div class="totals">
										<div class="points team1"><?php echo $statistics->totals->points_team1;?></div>
										<div class="goals team1"><?php echo $statistics->totals->goals_team1;?></div>
										<div class="goals team2"><?php echo $statistics->totals->goals_team2;?></div>
										<div class="points team2"><?php echo $statistics->totals->points_team2;?></div>
									</div>
								<?php endif;?>
								<!--статистика тура-->
								<?php 
									$times = array('time1'=>"Тайм 1");
									if(strtotime($model->date_cap) < time()) $times['time2'] = "Тайм 2";
									
									foreach($times as $key=>$header):
										if(isset($statistics->$key)):?>
										<h4><?php echo $header;?></h4>
										<table class="players">
											<td class="players1">
												<?php
                                                $players1 = isset($statistics->$key->players1) ? $statistics->$key->players1 : null;
												$count1 = empty($players1) ? 0 : count(get_object_vars($players1));
                                                if($count1 < 2):?>
													<div class="absent">
														<img src="/images/site/sudoku_absent_team.png"><br>Неявка
													</div>
												<?php else:?>
													<?php foreach($statistics->$key->players1 as $pl):?>
														<div class="player"><?php
															$sp = SudokuTeamPlayers::model()->findByPk($pl->info->id_player); 
															echo $pl->info->name_player;
															if(!empty($sp)):
														?>&nbsp;<span>(<?php echo $sp->user->display_name;?>)</span><?php endif;?></div>
														<div class="games">
															<?php foreach($pl->games as $game):?>
																<div class="game">
																	<div class="pull-right <?php echo ($game->computed)?"goals":"empty";?>">
																		<?php echo ($game->computed)?$game->goals:"&mdash;"; ?>
																	</div>
																	<div class="pull-right <?php echo ($game->computed)?"points":"empty";?>">
																		<?php echo ($game->computed)?$game->points:"&mdash;";?>
																	</div>
																	<?php $prognosis = json_decode($game->prognosis); ?>
																	<div class="prognosis">
																		<span><?php echo $prognosis->p1;?></span>
																		<span><?php echo $prognosis->p2;?></span>
																		<span><?php echo $prognosis->p3;?></span>
																	</div>
																</div>
															<?php endforeach;?>
														</div>
													<?php endforeach;?>
												<?php endif;?>
												<!--выводим запасных с их баллами-->
												<span style="display:none;"><?php
													$tm = str_replace("time","",$key);
													$bench = $pair->team1->getLineups($model->id, $tm, true);
												?></span>
												<div class="team_lineup"><?php foreach($bench as $player):
													$sp = SudokuTeamPlayers::model()->findByPk($player['info']['id_player']); 
												?>
													<div class="player">
														<div class="prognosis">
															<?php foreach($player['prognosis'] as $id_game=>$jpr):
																if(!empty($jpr)): $pr = json_decode($jpr['json']);?>
																	<div class="row">
																		<span><?php echo $pr->p1;?></span>
																		<span><?php echo $pr->p2;?></span>
																		<span><?php echo $pr->p3;?></span>
																		<span class="points"><?php echo ($jpr['points']==0)?"-":$jpr['points'];?></span>
																	</div>
																<?php else: ?>
																	<div class="row empty">-</div>
																<?php endif; ?>
															<?php endforeach;?>
														</div>
														<div class="pull-left nameline">
															<div class="name"><?php echo $player['info']['name_player'].'<br>('.$sp->user->display_name.')';?></div>
														</div>
													</div>
												<?php endforeach;?></div>
											</td>
											<td class="players2">
												<?php
												$players2 = isset($statistics->$key->players2) ? $statistics->$key->players2 : null;
												$count2 = empty($players2) ? 0 : count(get_object_vars($players2));
												if($count2 < 2):?>
													<div class="absent">
														<img src="/images/site/sudoku_absent_team.png"><br>Неявка
													</div>
												<?php else:?>
													<?php foreach($statistics->$key->players2 as $pl):?>
														<div class="player"><?php
															$sp = SudokuTeamPlayers::model()->findByPk($pl->info->id_player); 
															echo $pl->info->name_player;
															if(!empty($sp)):
														?>&nbsp;<span>(<?php echo $sp->user->display_name;?>)</span><?php endif;?></div>
														<div class="games">
															<?php foreach($pl->games as $game):?>
																<div class="game">
																	<div class="pull-left <?php echo ($game->computed)?"goals":"empty";?>">
																		<?php echo ($game->computed)?$game->goals:"&mdash;"; ?>
																	</div>
																	<div class="pull-left <?php echo ($game->computed)?"points":"empty";?>">
																		<?php echo ($game->computed)?$game->points:"&mdash;";?>
																	</div>
																	<?php $prognosis = json_decode($game->prognosis); ?>
																	<div class="prognosis pull-right">
																		<span><?php echo $prognosis->p1;?></span>
																		<span><?php echo $prognosis->p2;?></span>
																		<span><?php echo $prognosis->p3;?></span>
																	</div>
																</div>
															<?php endforeach;?>
														</div>
													<?php endforeach;?>
												<?php endif;?>
												<!--выводим запасных с их баллами-->
												<span style="display:none;"><?php
													$tm = str_replace("time","",$key);
													$bench = $pair->team2->getLineups($model->id, $tm, true);
												?></span>
												<div class="team_lineup"><?php foreach($bench as $player):
													$sp = SudokuTeamPlayers::model()->findByPk($player['info']['id_player']); 
												?>
													<div class="player">
														<div class="prognosis">
															<?php foreach($player['prognosis'] as $id_game=>$jpr):
																if(!empty($jpr)): $pr = json_decode($jpr['json']);?>
																	<div class="row">
																		<span><?php echo $pr->p1;?></span>
																		<span><?php echo $pr->p2;?></span>
																		<span><?php echo $pr->p3;?></span>
																		<span class="points"><?php echo ($jpr['points']==0)?"-":$jpr['points'];?></span>
																	</div>
																<?php else: ?>
																	<div class="row empty">-</div>
																<?php endif; ?>
															<?php endforeach;?>
														</div>
														<div class="pull-left nameline text-left">
															<div class="name"><?php echo $player['info']['name_player'].'<br>('.$sp->user->display_name.')';?></div>
														</div>
													</div>
												<?php endforeach;?></div>
											</td>
										</table>
									<?php else:?>
										<h4><?php echo $header;?></h4>
										<table class="players">
											<tr>
												<td class="players1 absent">
													<img src="/images/site/sudoku_absent_team.png"><br>Неявка
												</td>
												<td class="players1 absent">
														<img src="/images/site/sudoku_absent_team.png"><br>Неявка
												</td>
											</tr>
										</table>
									<?php endif;?>
								<?php endforeach;?>
								
								<?php if(strtotime($model->date_cap) > time()):
									//$pair = пара играющих команд 
								?>
									<!--дедлайн второго тайма еще не прошел, выводим весь состав и их прогнозы-->
									<h4>Тайм 2: составы команд</h4>
									<table class="players">
										<tr>
											<td class="players players1">
												<div class="team_lineup">
													<span style="display:none;"><?php $tour_team2_prognoses = $pair->team1->getLineups($model->id,2);?></span>
													<?php foreach($tour_team2_prognoses as $player):
														$sp = SudokuTeamPlayers::model()->findByPk($player['info']['id_player']); 
													?>
														<div class="player">
															<div class="prognosis">
																<?php foreach($player['prognosis'] as $id_game=>$jpr):
																	if(!empty($jpr)): $pr = json_decode($jpr['json']); ?>
																		<div class="row">
																			<span><?php echo $pr->p1;?></span>
																			<span><?php echo $pr->p2;?></span>
																			<span><?php echo $pr->p3;?></span>
																		</div>
																	<?php else: ?>
																		<div class="row empty">-</div>
																	<?php endif; ?>
																<?php endforeach;?>
															</div>
															<div class="pull-left nameline">
																<div class="name"><?php echo $player['info']['name_player'].'<br>('.$sp->user->display_name.')';?></div>
															</div>
														</div>
													<?php endforeach;?>
												</div>
											</td>
											<td class="players2 players">
												<div class="team_lineup">
													<span style="display:none;"><?php $tour_team2_prognoses = $pair->team2->getLineups($model->id,2);?></span>
													<?php foreach($tour_team2_prognoses as $player):
														$sp = SudokuTeamPlayers::model()->findByPk($player['info']['id_player']); 
													?>
														<div class="player">
															<div class="prognosis">
																<?php foreach($player['prognosis'] as $id_game=>$jpr):
																	if(!empty($jpr)): $pr = json_decode($jpr['json']);?>
																		<div class="row">
																			<span><?php echo $pr->p1;?></span>
																			<span><?php echo $pr->p2;?></span>
																			<span><?php echo $pr->p3;?></span>
																		</div>
																	<?php else: ?>
																		<div class="row empty">-</div>
																	<?php endif; ?>
																<?php endforeach;?>
															</div>
															<div class="pull-left nameline">
																<div class="name"><?php echo $player['info']['name_player'].'<br>('.$sp->user->display_name.')';?></div>
															</div>
														</div>
													<?php endforeach;?>
												</div>
											</td>
										</tr>
									</table>
								<?php endif;?>
							</div>
						</div>
					<?php endforeach;?>
				<?php endif;?>
			</div>
		</div>
		<div class="col-lg-6 right">
			<div class="league_sidebar">
				<!--сайдбар-->
			</div>
		</div>
	</div>
</div>	
