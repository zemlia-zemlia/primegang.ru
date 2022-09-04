<?php

$this->pageTitle = "Турнир VIVA SUDOKU!";

	/* @var $this SudokuController */
	$sudokuPlayer = null;
	
	$hasCurrentTour = false; //есть текущий тур
	$isUser = false; //пользователь зарегистрирован
	$hasTeam = false; //участвует в команде по судоку
	$teamActive = false;
	$teamPlays = false; //команда играет в туре
	
	$currentTour = SudokuTours::currentTour();
	if(!empty($currentTour)) $hasCurrentTour = true;
	
	$isUser = !Yii::app()->user->isGuest;
	
	if($isUser) {
		$sudokuPlayer = SudokuTeamPlayers::currentPlayer();
		if(!empty($sudokuPlayer)) $hasTeam = true;
	}
	
	$teamActive = $hasTeam && $sudokuPlayer->team->active;
	
	if($teamActive && $hasCurrentTour) {
		$tourTeams = SudokuToursTeams::model()->find(
			"id_tour=:id_tour and (id_sudoku_team1=:id_team or id_sudoku_team2=:id_team)", 
			array("id_tour"=>$currentTour->id, "id_team"=>$sudokuPlayer->team->id)
		);
		if(!empty($tourTeams)) $teamPlays = true;
	}
?>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="league">
				<h1 class="league_title"><span class="sudoku"></span>VIVA SUDOKU!</h1>
				<img src="/images/site/league_logo_sudoku.png">
				<div class="tours sudoku">
					<?php if(!$hasCurrentTour):?>
						<!--нет туров для прогнозирования-->
					<?php elseif(strtotime($currentTour->date) < time()):?>
						<!-- дедлайн тура прошел, выводим онлайн текущего тура -->
						<?php $this->renderPartial("_tourOnline", array('currentTour'=>$currentTour));?>
					<?php elseif(!$isUser ):?>
						<!--игрок не зарегистрирован и не может делать прогнозы-->
						<h1>Ой.</h1>
						<p><a href="/user/login">Войдите</a> или <a href="/user/register">зарегистрируйтесь</a>, чтобы сделать прогноз на тур судоку.</p>
					<?php elseif(!$hasTeam):?>
						<!--Игрок не состоит ни в какой команде
						<h1>Ой.</h1>
						<p>Вы не состоите ни в какой команде и не можете делать прогнозы.</p>
						<p><a href="/sudoku/teamrequest">Отправьте заявку на команду</a>, и если/когда администратор ее утвердит, вы сможете участвовать в турнире Судоку.</p>-->
					<?php elseif(!$teamActive):?>
						<!--команда игрока не активна-->
						<p>Ваша команда пока не допущена к участию в турнире Судоку. На <a href="/sudoku/team">странице команды</a> могут быть пояснения по этому вопросу.</p>
					<?php elseif(!$teamPlays):?>
						<!--команда игрока не играет в этом турнире-->
						<p>Ваша команда <a href="/sudoku/team"><?php echo $sudokuPlayer->team->name;?></a> не играет в этом туре турнира Судоку.</p>
					<?php else:?>
						<!--все проверки пройдены, выводим форму прогнозирования-->
						<?php $this->renderPartial('_tourPrognosis', array(
							'currentTour'=>$currentTour,
							'currentPrognosis'=>$currentPrognosis,
							'sudokuPlayer'=>$sudokuPlayer,
						)); ?>
					<?php endif;?>
					
					<!--остальные туры для вывода результатов-->
					<?php $this->renderPartial('_prevTourResults', array(
						'hasCurrentTour'=>$hasCurrentTour,
						'currentTour'	=>$currentTour,
                        'season' => $season,
					)); ?>
					
					<!--выводим турнирную таблицу по судоку-->
					<?php
					// выборка списка дивизионов для сезона
					$res = Yii::app()->db->createCommand('select `divisions`, `division_names` from `sudoku_seasons` where `id` = '.$season->id.' limit 1')->queryAll();
					$divisions = $res[0]['divisions'];
					$division_names = explode(';', $res[0]['division_names']);
					$division_select = array();
					for ($d = 0; $d < $divisions; $d++):
						$division_select[$d + 1] = isset($division_names[$d]) && $division_names[$d] ? $division_names[$d] : 'Дивизион '.($d + 1);
					?>
						<div class="league_statistics sudoku">
							<?php
							if (!$d)
								echo '<h3>Итоги прошедших туров</h3><br>';

							if ($divisions > 1)
								echo '<h3>'.$division_select[$d + 1].'</h3>';

							?>
							<table class="table">
								<tr>
									<th class="place_legend">№</th>
									<th class="user_legend sudoku">Команда</th>
									<th class="numbers">И</th>
									<th class="numbers">В</th>
									<th class="numbers">Н</th>
									<th class="numbers">П</th>
									<th class="numbers">Г</th>
									<th class="numbers">О</th>
								</tr>
								<?php 
								$i = 0;
								foreach($tourTable[$d] as $ttRow):
									$steam = SudokuTeams::model()->findByPk($ttRow['id_team']);
									if(empty($steam)) continue;
									$i++;

								?>
								<tr>
									<td class="place"><?php echo $i;?></td>
									<td class="team">
										<?php if(!empty($steam->image_url)):?><img src="<?php echo $steam->image_url;?>"><?php endif;?>
										<h6><?php echo $steam->name;?></h6>
										
									</td>
									<td><?php echo $ttRow['tour_count'];?></td>
									<td><?php echo $ttRow['win'];?></td>
									<td><?php echo $ttRow['tee'];?></td>
									<td><?php echo $ttRow['fail'];?></td>
									<td class="goals"><?php echo $ttRow['goals'];?>-<?php echo $ttRow['misses'];?></td>
									<td class="points"><?php echo $ttRow['points'] ;?></td>
								</tr>
								<?php endforeach;?>
							</table>
						</div>
					<?php endfor;?>
					
				</div>
			</div>
		</div>			
		<div class="col-lg-6 right">
			<div class="league_sidebar">

				<div class="usermenu">
					<?php if(!$isUser):?>
						
					<?php elseif(!$hasTeam):?>
						<a href="/sudoku/teamrequest">Заявка<br> на регистрацию команды</a>
						<span>Пока вы не состоите ни в какой команде, вы не можете делать прогнозы.</span>
					<?php else:?>
						<a href="/sudoku/team"><img src="/images/site/sudoku_team.png"><br>Ваша команда</a>
					<?php endif;?>
				</div>
				
				<?php $this->widget('SudokuSidebar', array(
					'type'	=>"bombers",
				)); ?>
				<?php $this->widget('SudokuSidebar', array(
					'type'	=>"goleadors",
				)); ?>

			</div>
		</div>
	</div>
</div>