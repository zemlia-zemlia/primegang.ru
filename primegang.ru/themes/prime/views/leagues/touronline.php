<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="league">
				<h1 class="league_title"><span class="<?php echo $model->league->alias;?>"></span><?php echo $model->league->name; ?></h1>
				<img src="/images/site/league_logo_<?php echo $model->league->alias;?>.png">
				
				<!--ПРОГНОЗЫ ИГРОКОВ НА ТЕКУЩИЙ ТУР-->
				<div class="tours">
					<?php foreach($model->games as $game):
						if(strtotime($game->date) > time()) continue;
					?>
					<div class="tour prev byothers">
						<div class="tour_results">
							<h3>Прогнозы игроков</h3>
							<div class="tour_result">
								<div class="tour_result_team first"><span><?php echo $game->team1->name?></span></div>
								<div class="tour_result_team second"><span><?php echo $game->team2->name?></span></div>
							</div>

							<div class="prognoses">
							<?php foreach($game->prognoses as $p):?>
							<div class="item">
								<span><?php echo $p->score_team1_total;?></span><span><?php echo $p->score_team2_total;?></span>
								<div><?php echo $p->user->display_name;?></div>								
							</div>
							<?php endforeach; ?>
							</div>
						</div>
					</div>
					<?php endforeach;?>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="league_sidebar">
				<?php $this->widget('Statistics', array(
					'type'	=>"league",
					'dataId'=> $model->league->id,
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
		