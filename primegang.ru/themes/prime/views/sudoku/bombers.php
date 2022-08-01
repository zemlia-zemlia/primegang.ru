<?php $this->pageTitle = "VIVA SUDOKU! — лучшие бомбардиры"; ?>
<span style="display:none;"><?php $stats = PrognoseFunctions::getSudokuPointsStats(null, $season->alias);?></span>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="league touronline">
				<h1 class="league_title"><span class="sudoku"></span>Лучшие бомбардиры турнира VIVA SUDOKU!</h1>
                <h4 style="text-align:center; margin-bottom: 10px;"><?= $season->name;?></h4>

				<img src="/images/site/league_logo_sudoku.png">
				<div class="tours sudoku league_statistics ">
					<table class="table bombers">
						<tr>
							<th class="place">Место</th>							
							<th class="player">Игрок</th>
							<th class="team">Команда</th>
							<th class="games">Игры</th>
							<th class="points">Очки</th>
						</tr>
						<?php foreach($stats as $key=>$row):
							$u = User::model()->findByPk($row['id_user']);
						?>
						<tr class="<?php
						echo ($row['id_user'] == Yii::app()->user->id) ? "active" : "";
						?>">
							<td class="place"><?php echo $key+1;?></td>
							<td class="player"><img src="/<?php echo (empty($u->profile->userimage))?"images/site/no_avatar.png":$u->profile->userimage; ?>"/><h6><?php echo $u->display_name;?></h6> <span><?php echo $row['name_player'];?></span></td>
							<td class="team"><?php echo $row['name_team'];?></td>
							<td class="games"><?php echo $row['tour_count'];?></td>
							<td class="points"><?php echo $row['points'];?></td>
						</tr>
						<?php endforeach;?>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-6 right">
			<div class="league_sidebar">
				<!--сайдбар-->
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
