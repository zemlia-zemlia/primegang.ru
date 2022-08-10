<?php

$this->pageTitle = "Турнир VIVA SUDOKU!";


?>

<div class="row content">
	<div class="container">
		<div class="col-lg-12">
			<div class="league">

				<div class="tours sudoku">



                    <form action="/admin/sudokuseasons/table_edit" method="post" >
					<!--выводим турнирную таблицу по судоку-->
					<?php

					$division_select = [];
					for ($d = 0; $d < $divisions; $d++):
						$division_select[$d + 1] =
                        isset($division_names[$d]) && $division_names[$d] ? $division_names[$d] :
                            'Дивизион '.($d + 1);
					?>
						<div class="league_statistics sudoku">
							<?php
							if (!$d)
								echo '<h3>Редактировать таблицу</h3><br>';

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
									<th class="numbers">Г/З</th>
									<th class="numbers">Г/П</th>
									<th class="numbers">О</th>
									<th class="numbers">О/Д</th>
								</tr>
								<?php
								$i = 0;
								foreach($tourTable[$d] as $ttRow):
									$steam = SudokuTeams::model()->findByPk($ttRow['id_team']);
									if(empty($steam)) continue;
									$i++;
									$idTour = $ttRow['idtour'];
									$addPoints = Addpoints::model()->find('id_sudoku_team='. $ttRow['id_team'].
                                        ' AND id_tour='. $idTour);
//                                    CVarDumper::dump($ttRow, 5, true);
//                                    $points = $addPoints ? ($ttRow['points'] + $addPoints->points) : $ttRow['points'];

                                    ?>

                                
								<tr id="<?= $ttRow['id_team'];?>">
									<td class="place"><?= $i;?></td>
									<td class="team">
										<?php if(!empty($steam->image_url)):?><img width="50" src="<?php echo $steam->image_url;?>"><?php endif;?>
										<h6><?php echo $steam->name;?></h6>
										
									</td>
									<td><?= $ttRow['tour_count'];?></td>
                                    <td><?= $ttRow['win'];?></td>
                                    <td><?= $ttRow['tee'];?></td>
                                    <td><?= $ttRow['fail'];?></td>
                                    <td>
                                        <input size="3" type="text" name="<?= $ttRow['id_team'] ?>[goals]"
                                               value="<?= $ttRow['goals'];?>"/>
                                    </td>

                                    <td>
                                        <input size="3" type="text" name="<?= $ttRow['id_team'] ?>[missing]"
                                               value="<?= $ttRow['misses'];?>"/>

                                    </td>
                                    <td>
                                        <input size="3" type="text" name="<?= $ttRow['id_team'] ?>[points]"
                                               value="<?= $ttRow['points'];?>" disabled="disabled"/>
                                    </td>
                                     <td>
                                        <input size="3" type="text" name="<?= $ttRow['id_team'] ?>[addpoints]"
                                               value="<?= $addPoints ? $addPoints->points : 0;?>"/>
                                    </td>

								</tr>
								<?php endforeach;?>
							</table>
						</div>
					<?php endfor;?>
                        <input type="hidden" name="idtour" value="<?= $idTour ?>">
                        <input type="submit" value="Сохранить">
                    </form>
					
				</div>
			</div>
		</div>			

	</div>
</div>