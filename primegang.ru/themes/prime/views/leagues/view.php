<?php
/* @var $this LeaguesController */
/* @var $model Leagues */

$this->breadcrumbs=array(
	'Leagues'=>array('index'),
	$model->name,
);

$this->pageTitle = $model->name;
//CVarDumper::dump($archive, 5, true);
?>

<!--инициализируем прогнозы-->
<script>
	$(function() {
		$("input[data-type=score]").blur(function() {
			var gameId = $(this).attr("data-gameid");
			var scores = $("input[data-type=score][data-gameid="+gameId+"]");
			
			var emptyVals = false;
			var vals = {};
			for(i=0; i<scores.length; i++) {
				var _score = parseInt($(scores[i]).val());
				if(isNaN(_score) || _score === "" || _score === undefined) {
					emptyVals = true; break;
				}
				vals[$(scores[i]).attr("data-column")] = _score;
			}
			
			if(!emptyVals) {
				var _url = "/ajax/updateprognosis";
				
				var _dd = {}; _dd[gameId] = vals;
				var _data = {"Prognosis":_dd};
				
				$.ajax({
					'type':"POST",
					'url':_url,
					'data':_data,
					'success':function(data) {
						//$('.tour_result[data-gameid='+gameId+']').addClass('sent');

						if(!_data.success == "true") alert(_data.message);
						else $('.tour_result[data-gameid='+gameId+']').addClass('sent');
					}
				});
			}
		});
	});
</script>


<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="league">
				<h1 class="league_title"><span class="<?php echo $model->alias;?>"></span><?php echo $model->name; ?></h1>
				<img src="/images/site/league_logo_<?php echo $model->alias;?>.png">
			
				<div class="tours">
					<!-- следующий по дате тур (для прогнозов) -->
					<?php
					$tour = Tours::currentTour($model->id);
					if(!empty($tour)):
					?>
					<div class="tour next">
						<a class="btn-block tourinfo" data-toggle="collapse" href="#collapse<?php echo $tour->id;?>" aria-expanded="true" aria-controls="collapseExample">
							<div class="tour_next_prev">Следующий тур</div>
							<div class="tour_date"><?php if(!empty($tour->season))?>(<?php echo $tour->season->name;?>)</div>
							<div class="tour_number"><?php echo $tour->name;?></div>					
						</a>
						<div class="collapse tour_results in" id="collapse<?php echo $tour->id;?>" aria-expanded="true">
							<?php if(Yii::app()->user->isGuest):?>
								<div class="alert alert-danger" style="margin:20px;" role="alert">
									<a href="/user/register">Зарегистрируйтесь</a> или <a href="/user/login">войдите</a>, чтобы сделать прогноз!
								</div> 
							<?php else: ?>
								<form class="form form-prognosis" method="POST" action="/prognosis/update">
									<?php $games = Games::model()->findAll(
										"id_league=:id_league and id_tour=:id_tour",
										array('id_league'=>$model->id,"id_tour"=>$tour->id)
									);
									foreach($games as $game):
										$prognosis = Prognosis::model()->find(
											"id_game = :id_game AND id_user=:id_user",
											array("id_game"=>$game->id, "id_user"=>Yii::app()->user->id)
										);	
										$hasProg = ($prognosis !== null);
									?>
										<div class="tour_result <?php if($hasProg) echo "sent";?>" data-gameid="<?php echo $game->id;?>">
											<span><?php echo $game->date; ?><b class="msg" style="display:none;">прогноз принят</b></span>
											<div class="tour_result_team first">
												<span><?php echo $game->team1->name;?></span>
												<?php if(strtotime($game->date) >= time()):?>
													<input name="Prognosis[<?php echo $game->id;?>][score_team1]" value="<?php 
														if($hasProg) echo $prognosis->score_team1_total;									
													?>" data-type="score" data-column="score_team1" data-gameid="<?php echo $game->id;?>"/>
												<?php else:?>
													<!--закрываем прогноз-->
													<div class="pull-right">
														<?php if($hasProg): echo "<b style='font-size:14px;'>".$prognosis->score_team1_total."</b>"; else:?><span class="closed glyphicon glyphicon-remove"></span><?php endif;?>
													</div>
												<?php endif;?>
												
											</div>
											<div class="tour_result_team second">
												<span><?php echo $game->team2->name;?></span>
												<?php if(strtotime($game->date) >= time()):?>
													<input name="Prognosis[<?php echo $game->id;?>][score_team2]" value="<?php 
														if($hasProg) echo $prognosis->score_team2_total;									
													?>" data-type="score" data-column="score_team2" data-gameid="<?php echo $game->id;?>">
												<?php else:?>
													<!--закрываем прогноз-->
													<div class="pull-right">
														<?php if($hasProg): echo "<b style='font-size:14px;'>".$prognosis->score_team2_total."</b>"; else:?><span class="closed glyphicon glyphicon-remove"></span><?php endif;?>
													</div>
												<?php endif;?>
											</div>
										</div>
									<?php endforeach;?>
								</form>
							<?php endif;?>
							<div class="protocol">
								<a href="/leagues/touronline/<?php echo $tour->id;?>">Прогнозы игроков</a>
							</div>
						</div>
					</div>
					<?php 
						endif;
					?>
                    <!-- бывшие туры (для результатов) -->
                    <?php

                    $currentTour = Tours::currentTour($model->id);

                    $criteria = new CDbCriteria();
                    $criteria->order = "tour_number DESC";
                    if (!($season == '0')) {
                        $criteria->condition =
                            "t.id_league=:id_league AND t.date <= :now AND t.id_season=:season";
                        $criteria->params = [
                            "id_league" => $model->id,
                            "now" => date('Y-m-d'),
                            "season" => $season
                        ];
                    }
                    else {
                        $currentSeasons = Seasons::model()->findAll('1=1');
                        $currentSeason = array_pop($currentSeasons);
                        $criteria->condition =
                            "t.id_league=:id_league AND t.date <= :now AND t.id_season=:season";
                        $criteria->params = [
                            "id_league" => $model->id,
                            "now" => date('Y-m-d'),
                            "season" => $currentSeason->id
                        ];
                    }
                    $tours = Tours::model()->with('games')->findAll($criteria);
                    $i = 0;
                    foreach($tours as $tour):
                        if(!empty($currentTour) && $currentTour->id == $tour->id) continue;
                        $expanded = ($i==0)?true:false;
                        $i++;
                        ?>
                        <div class="tour prev">
                            <a class="btn-block tourinfo" data-toggle="collapse" href="#collapse<?php echo $tour->id;?>" aria-expanded="<?php echo ($expanded)?"true":"false"; ?>" aria-controls="collapseExample">
                                <div class="tour_next_prev">&nbsp;</div>
                                <div class="tour_date"><?php if(!empty($tour->date)) echo $tour->date;?></div>
                                <div class="tour_number"><?php echo $tour->name;?></div>
                            </a>
                            <div class="collapse <?php echo ($expanded)?"in":""; ?>" id="collapse<?php echo $tour->id;?>"  aria-expanded="<?php echo ($expanded)?"true":"false"; ?>">
                                <div class="tour_results">
                                    <?php
                                    $games = $tour->games;
                                    $gameIds = array();
                                    foreach($games as $game):
                                        $gameIds[] = $game->id;
                                        $user_prognosis = Prognosis::loadUserGamePrognosis($game->id);
                                        ?>
                                        <div class="tour_result">
                                            <span><?php echo $game->date; ?></span>
                                            <div class="tour_result_team first">
                                                <span><?php echo $game->team1->name;?></span>
                                                <span class="result"><?php echo $game->score_team1_total;?></span>
                                                <?php if(!empty($user_prognosis)):?>
                                                    <span class="prognosis" data-toggle="tooltip" data-placement="top" title="Ваша ставка"><?php echo $user_prognosis->score_team1_total;?></span>
                                                <?php endif;?>
                                            </div>
                                            <div class="tour_result_team second">
                                                <span><?php echo $game->team2->name;?></span>
                                                <span class="result"><?php echo $game->score_team2_total;?></span>
                                                <?php if(!empty($user_prognosis)):?>
                                                    <span class="prognosis" data-toggle="tooltip" data-placement="top" title="Ваша ставка"><?php echo $user_prognosis->score_team2_total;?></span>
                                                <?php endif;?>
                                            </div>
                                            <?php if(!empty($user_prognosis)):?>
                                                <div class="points">Ваши баллы <span><?php echo $user_prognosis->balls;?></span></div>
                                            <?php endif;?>
                                        </div>
                                    <?php endforeach;?>
                                </div>

                                <!--полная статистика тура-->
                                <?php
                                $fullStats = $tour->getTourFullStats();
                                if(!empty($fullStats)):?>
                                    <div class="leaders">
                                        <h3>Результаты тура</h3>
                                        <table class="table">
                                            <tr>
                                                <th class="place_legend">№</th>
                                                <th class="user_legend">Пользователь</th>
                                                <th>Игры</th>
                                                <th>Очки</th>
                                                <th>Точный счет</th>
                                                <th>Ничьи</th>
                                                <th>И+Р</th>
                                                <th>Исход</th>
                                            </tr>
                                            <?php foreach($fullStats as $key=>$stat):?>
                                                <tr class="<?php echo (!empty($stat->user) && $stat->user->id == Yii::app()->user->id) ? "active" : "";?>">
                                                    <td class="place"><?php echo $key+1;?></td>
                                                    <td class="user"><?php echo (!empty($stat->user)) ? $stat->user->display_name : "Удален";?></td>
                                                    <td><?php echo $stat->game_count;?></td>
                                                    <td class="points"><?php echo $stat->points;?></td>
                                                    <td><?php echo $stat->fact;?></td>
                                                    <td><?php echo $stat->tee;?></td>
                                                    <td><?php echo $stat->diff;?></td>
                                                    <td><?php echo $stat->res;?></td>
                                                </tr>
                                            <?php endforeach;?>
                                        </table>
                                    </div>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endforeach;?>
					
				</div>
                <!--полная статистика по лиге-->
                <?php
                $league_stats = $model->getLeagueFullStats();
                if(!empty($league_stats)):
                    ?>
                    <div class="league_statistics">
                        <h3>Итоги прошедших туров</h3>
                        <table class="table">
                            <tr>
                                <th class="place_legend">№</th>
                                <th class="user_legend">Пользователь</th>
                                <th>Игры</th>
                                <th>Очки</th>
                                <th>Точный счет</th>
                                <th>Ничьи</th>
                                <th>И+Р</th>
                                <th>Исход</th>
                            </tr>
                            <?php foreach($league_stats as $key=>$stat):?>
                                <tr class="<?php echo (!empty($stat->user) && $stat->user->id == Yii::app()->user->id) ? "active" : "";?>">
                                    <td class="place"><?php echo $key+1;?></td>
                                    <td class="user"><?php echo (!empty($stat->user)) ? $stat->user->display_name : "Удален";?></td>
                                    <td><?php echo $stat->game_count;?></td>
                                    <td class="points"><?php echo $stat->points;?></td>
                                    <td><?php echo $stat->fact;?></td>
                                    <td><?php echo $stat->tee;?></td>
                                    <td><?php echo $stat->diff;?></td>
                                    <td><?php echo $stat->res;?></td>
                                </tr>
                            <?php endforeach;?>
                        </table>
                    </div>
                <?php endif;?>

			</div>
		</div>			
		<div class="col-lg-6">
			<div class="league_sidebar">
				<?php $this->widget('Statistics', array(
					'type'	=>"league",
					'dataId'=> $model->id,
					'limit' => 3,
					'view' 	=> "_sidebar",
				)); ?>
				<?php $this->widget('Statistics', array(
					'type'	=>"season",
					'dataId'=> "",
					'limit' => 3,
					'view' 	=> "_sidebar",
				)); ?>
			</div>
		</div>
	</div>
</div>


