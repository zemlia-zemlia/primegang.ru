<?php 
	//$model = new SudokuTeamAdmin();
	$model->load($sudokuPlayer->team);

	$sql = "SELECT id FROM sudoku_seasons ORDER BY id DESC LIMIT 1";
	$command = Yii::app()->db->createCommand($sql);
	$season_id = $command->queryAll()[0]['id'];
	$players_stats = array();
?>

<style>
	tr.strike td {text-decoration: line-through;}
	tr.strike td button {text-decoration: none;}

	td.position.change {background:#9CE29C;}
	tr td.buttons {text-align:left;}
	tr td.buttons button {display:none;}
	tr td.buttons button.deleteplayer {display:inline-block;}
	
	tr.captain td.buttons button.fireplayer {display:inline-block;}
	tr.captain td.buttons button.deleteplayer {display:none;}
	
	tr.vicecaptain	td.buttons button.fireplayer {display:inline-block;}
	tr.player	 	td.buttons button.setcaptain {display:inline-block;}
	tr.player	 	td.buttons button.setvicecaptain {display:inline-block;}
	div.nameline {overflow: hidden;}
	div.nameline div {float:left;}
	div.nameline .line {padding:1px 9px; background: #BA5C5C; margin-left:10px; margin-top:15px; color:#FFFFFF; border-radius: 3px;}
	div.nameline .line:empty {display:none;}
</style>

<script>
	var lines = [[0,0,0],[0,0,0]]; // массив использованных линий
	$(function() {
		
		//расставим selected, если в .player есть input[type=hidden].pplaying и все c value=1
		//выставляем line, если в .player есть input[type=hidden].pline и все с одним значением
		$(".sudokuTime .players .player").each(function(index, element) {
			var _playing = $(element).find("input.pplaying[type=hidden][value='1']");
			if(_playing.length == 3) $(element).addClass('selected');
			
			var _html = $(element).find("input.pline[type=hidden]").val();
			if(_html == 0 || _html == undefined || _html == null) _html = "";
			else lines[$(element).data("time")][_html - 1] = 1; // отметка, что линия занята
			$(element).find("div.line").html(_html);
		});
		//назначим выбор 3-х игроков в составе для .players.selectable
		$(".players.selectable a.player").click(function() {
			var _element = $(this);
			//если элемент уже выбран, снимаем выделение
			if(_element.hasClass("selected")) {
				var _html = _element.find("input.pline[type=hidden]").val();
				lines[_element.data("time")][_html - 1] = 0; // линия свободна
				_element.find("input.pplaying[type=hidden]").attr('value',0);
				_element.find("input.pline[type=hidden]").attr('value',0);
				_element.find("div.line").html("");
				
				_element.removeClass("selected");
			} else {
				//проверяем, выбрано ли больше 3 игроков
				var _parent = _element.parents(".players.selectable");
				var _selected = _parent.find(".player.selected");
				
				//проверяем, все ли прогнозы сделаны у этого игрока
				var _prognoses = _element.find("input.pplaying[type=hidden]");
				
				if(_selected.length < 3 && _prognoses.length == 3) {
					_element.find("input.pplaying[type=hidden]").attr('value',1);
					_element.addClass("selected");

					//var _line = _parent.find(".player.selected").length; // это очень плохо
					var _line = 1;
					while(_line < 4 && lines[_element.data("time")][_line - 1]) _line++; // поиск не занятой линии
					lines[_element.data("time")][_line - 1] = 1; // эта линия теперь занята

					_element.find("div.line").html(_line);
					_element.find("input.pline[type=hidden]").attr('value',_line);
				}
			}
			return false;
		});
		
		//сохраняем играющие прогнозы
		$('form#playingprognosis-form').submit(function(){
			var _data	 = $(this).serialize();
			var _url	 = $(this).attr('action');
			$.ajax({
				'type'	:'POST',
				'url'	:_url,
				'data'	:_data,
				'success':function(data) {
					alert("Изменения сохранены");
				}
			});
			return false;
		});
	
		$(function() {
			$('div[data-actiongroup=manageplayers] button').click(function() {
				var _element	 = $(this);
				var _action		 = _element.attr('data-action');
				var _class		 = _element.attr('data-class');
				var _idplayer	 = _element.find("input[name=id_player]").val();
				
				if(_action=='deleteplayer')
					if(!confirm('Действительно хотите удалить игрока?')) return false;
				
				_url = "/sudoku/manageplayer";
				_data = {'Manageplayer':{'action':_action,'id':_idplayer}};
				
				$.ajax({
					'type'		:'POST',
					'url'		:_url,
					'data'		:_data,
					'success'	:function(data) {
						var _tr = _element.parents('tr');
						if(_action=="deleteplayer") {
							_tr.remove();
						} else {
							_tr.find('td.position').html(data);
							_tr.find('td.position').addClass('change');
							_tr.attr("class","");
							_tr.addClass(_class);
						}
					},
					'fail':function() {
						alert("При управлении игроками произошли ошибки! Обратитесь к разработчикам.");
					}
				}); 
			});		
		});
	});
</script>

<!--Пользователь не состоит в команде и может подать заявку-->
<?php if(Yii::app()->user->hasFlash('teamadmin')): ?>
<div class="flash-success">
	<?php echo Yii::app()->user->getFlash('teamadmin'); ?>
</div>
<?php endif;?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'teamadmin-form',
	'action'=>"",
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<?php echo $form->errorSummary($model); ?>
	
	<?php echo $form->hiddenField($model,'teamid'); ?>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'teamname'); ?>
		<?php echo $form->textField($model,'teamname',array('class'=>"form-control")); ?>
		<?php echo $form->error($model,'teamname'); ?>
	</div>
	
	<table class="table">
		<thead>
			<tr>
				<th colspan="4">Игроки</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($sudokuPlayer->team->players as $player): ?>
			<tr class="<?php if($player->captain):?>captain<?php elseif($player->vicecaptain):?>vicecaptain<?php else:?>player<?php endif;?>">
				<td class="position"><?php if($player->captain):?>Капитан<?php elseif($player->vicecaptain):?>Вице-капитан<?php else:?>Игрок<?php endif;?></td>
				<td class="name"><?php echo $player->name; ?></td>
				<td class="username"><?php echo (!empty($player->user)) ? $player->user->display_name : "Удален"; ?></td>
				<?php if($sudokuPlayer->captain):?>
					<td class="buttons">
						<div class="btn-group" role="group" aria-label="Управление игроками" data-actiongroup="manageplayers">
							<button type="button" class="btn btn-danger deleteplayer" data-action="deleteplayer" data-class="remove" title="Удалить игрока">
								<input type="hidden" name="id_player" value="<?php echo $player->id;?>">
								<span class="glyphicon glyphicon-remove"></span></button>
							<button type="button" class="btn btn-default fireplayer" data-action="fireplayer" data-class="player" title="Снять с должности">
								<input type="hidden" name="id_player" value="<?php echo $player->id;?>">
								<span class="glyphicon glyphicon-ban-circle"></span></button>
							<button type="button" class="btn btn-default setcaptain" data-action="setcaptain" data-class="captain" title="Сделать капитаном">
								<input type="hidden" name="id_player" value="<?php echo $player->id;?>">
								<span class="glyphicon glyphicon glyphicon-star"></span></button>
							<button type="button" class="btn btn-default setvicecaptain" data-action="setvicecaptain" data-class="vicecaptain" title="Сделать вице-капитаном">
								<input type="hidden" name="id_player" value="<?php echo $player->id;?>">
								<span class="glyphicon glyphicon-star-empty"></span></button>
						</div>					
					</td>
				<?php endif;?>
			</tr>
		<?php endforeach; ?>
		<?php if(count($sudokuPlayer->team->players) < 6 && $sudokuPlayer->captain):?>
			<tr>
				<td>Новый игрок</td>
				<td>
					<?php echo $form->textField($model,'addPlayerLogin',array('class'=>"form-control","placeholder"=>"Логин")); ?>
					<?php echo $form->error($model,'addPlayerLogin'); ?>
				</td>
				<td>
					<?php echo $form->textField($model,'addPlayerName',array('class'=>"form-control","placeholder"=>"Псевдоним")); ?>
					<?php echo $form->error($model,'addPlayerName'); ?>
				</td>
				<td>&nbsp;</td>
			</tr>
		<?php endif;?>
		</tbody>
	</table>
	<?php if($sudokuPlayer->captain):?>
		<div class="row buttons">
			<button class="btn btn-primary submit">Добавить игрока</button>
		</div>
		<div class="legend">
			<p><span class="glyphicon glyphicon-remove"></span> — удалить игрока из команды.</p>
			<p><span class="glyphicon glyphicon-ban-circle"></span> — снять игрока с должности капитана или вице-капитана.</p>
			<p><span class="glyphicon glyphicon-star"></span> — назначить игрока капитаном.</p>
			<p><span class="glyphicon glyphicon-star-empty"></span> — назначить игрока вице-капитаном.</p>
		</div>
	<?php endif;?>

<?php $this->endWidget(); ?>
</div><!-- form -->


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
		<div class="sudokuTour form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'playingprognosis-form',
				'action'=>"/sudoku/updateplayingprognosis",
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
			)); ?>
			<!--назначение прогнозов по текущему туру судоку-->
			<h2>Текущий тур по Судоку: <?php echo $currentSudokuTour->name; ?></h2>
			<div class="deadline">Дедлайн первого тайма: <b><?php echo $currentSudokuTour->date; ?></b></div>
			<div class="deadline">Дедлайн второго тайма: <b><?php echo $currentSudokuTour->date_cap; ?></b></div>
			<p class="hint">Игроки имеют право подавать прогнозы только до первого дедлайна. Капитаны имеют право менять состав на второй тайм до дедлайна второго тайма.</p>
			
			<?php
				$availableTime1 = (time() < strtotime($currentSudokuTour->date));
				$availableTime2 = (time() < strtotime($currentSudokuTour->date_cap));
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
						<?php foreach($sudokuPlayer->team->players as $pl):

	$sql = "SELECT COUNT(DISTINCT g.id_tour) AS tour_count, SUM(pr.balls) AS balls, SUM(pr.points) AS points
		FROM sudoku_prognosis pr LEFT JOIN sudoku_games AS g ON pr.id_game = g.id
		WHERE pr.computed = 1 AND pr.id_player = {$pl->id} AND g.id_season = {$season_id}";
	$command = Yii::app()->db->createCommand($sql);
	$players_stats[$pl->id] = $command->queryAll()[0];

						?>
							<?php if($avT1):?><a class="player" data-time="0" href="#"><?php else:?><div class="player" data-time="0"><?php endif;?>
								<div class="prognosis">
									<?php foreach($currentSudokuTour->games as $game):
										if($game->time <> 1) continue;
										$pr = SudokuPrognosis::model()->find(
											'id_player=:id_player and id_game=:id_game',
											array('id_player'=>$pl->id,'id_game'=>$game->id)
										);
										if(!empty($pr)):?>
											<div class="row">
												<input type="hidden" class="pplaying" name="PlayingPrognosis[<?php echo $game->id;?>][<?php echo $pr->id;?>][playing]" 	value="<?php echo $pr->playing;?>">
												<input type="hidden" class="pline"    name="PlayingPrognosis[<?php echo $game->id;?>][<?php echo $pr->id;?>][line]"		value="<?php echo $pr->line;?>">
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
									<div class="name"><?php echo $pl->name.' <i>('.$pl->user->display_name.')</i><br>
	баллы: '.(0+$players_stats[$pl->id]['points']).', голы: '.(0+$players_stats[$pl->id]['balls']).', туры: '.$players_stats[$pl->id]['tour_count'];?></div>
									<div class="line"></div>
								</div>
							<?php if($avT1):?></a><?php else:?></div><?php endif;?>
						<?php endforeach;?>
					</div>
					<p>Внимание! При выборе игрока основного состава используется незанятая линия с меньшим номером. </p>
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
							<?php if($avT2):?><a class="player" data-time="1" href="#"><?php else:?><div class="player" data-time="1"><?php endif;?>
								<div class="prognosis">
									<?php foreach($currentSudokuTour->games as $game):
										if($game->time <> 2) continue;
										$pr = SudokuPrognosis::model()->find(
											'id_player=:id_player and id_game=:id_game',
											array('id_player'=>$pl->id,'id_game'=>$game->id)
										);
										if(!empty($pr)):?>
											<div class="row">
												<input type="hidden" class="pplaying" name="PlayingPrognosis[<?php echo $game->id;?>][<?php echo $pr->id;?>][playing]" 	value="<?php echo $pr->playing;?>">
												<input type="hidden" class="pline"    name="PlayingPrognosis[<?php echo $game->id;?>][<?php echo $pr->id;?>][line]"		value="<?php echo $pr->line;?>">
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
									<div class="name"><?php echo $pl->name.' <i>('.$pl->user->display_name.')</i><br>
	баллы: '.(0+$players_stats[$pl->id]['points']).', голы: '.(0+$players_stats[$pl->id]['balls']).', туры: '.$players_stats[$pl->id]['tour_count'];?></div>
									<div class="line"></div>
								</div>
							<?php if($avT2):?></a><?php else:?></div><?php endif;?>
						<?php endforeach;?>
					</div>
					<p>Внимание! При выборе игрока основного состава используется незанятая линия с меньшим номером. </p>
				</div>
			</div>
		
			<div class="row buttons submit">
				<button class="btn btn-primary submit">Отправить состав на игру</button>
				<p>Внимание!</p>
				<p>Команда отправляется на игру и участвует в туре, если капитан или вице-капитан нажмут эту кнопку.</p>
				<p>Если игроки (минимум двое) сделали прогнозы, но состав не отправлен на игру,<br>
				то тогда автомат сам случайным образом определит состав.<br>
				Претензии за неоптимальный выбор основы не принимаются.</p>
			</div>
			<?php $this->endWidget(); ?>
		</div>
	<?php else:?>
		Ваша команда не играет в текущем туре VIVA SUDOKU!
	<?php endif;?>
<?php endif;?>
