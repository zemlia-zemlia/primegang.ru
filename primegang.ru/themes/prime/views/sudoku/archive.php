<?php
$this->pageTitle = "Архив турнира VIVA SUDOKU!";
?>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="league">
				<h1 class="league_title"><span class="sudoku"></span>VIVA SUDOKU!</h1>
				<img src="/images/site/league_logo_sudoku.png">
				<div class="tours sudoku">
                    <h1>Архив сезона <?php echo $season->name;?></h1>

					<!--остальные туры для вывода результатов-->
					<?php $this->renderPartial('_prevTourResults', array(
						'hasCurrentTour'=>false,
						'season'	=>$season,
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
								echo '<h3>Итоги туров сезона</h3><br>';

							if ($divisions > 1)
								echo '<h3>'.$division_select[$d + 1].'</h3>';

							?>
							<table class="table">
								<tr>
									<th class="place_legend">№</th>
									<th class="user_legend sudoku team">Команда</th>
									<th class="numbers">И</th>
									<th class="numbers">В</th>
									<th class="numbers">Н</th>
									<th class="numbers">П</th>
									<th class="numbers large">Г</th>
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
									<td class="points"><?php echo $ttRow['points'];?></td>
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
				<?php $this->widget('SudokuSidebar', array(
					'type'	=>"bombers",
                    'season' => $season->alias,
				)); ?>
				<?php $this->widget('SudokuSidebar', array(
					'type'	=>"goleadors",
					'season' => $season->alias,
				));?>

            </div>
        </div>

	</div>
</div>