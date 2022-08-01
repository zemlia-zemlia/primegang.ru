<?php

// тут делаем то, что нам нужно
$id_team1 = 39;
$id_team2 = 34;
$id_tour = 50;

$tp1 = PrognoseFunctions::sudoku_getTeamPlayers($id_team1, $id_tour);
$tp2 = PrognoseFunctions::sudoku_getTeamPlayers($id_team2, $id_tour);

$merged = PrognoseFunctions::sudoku_mergePlayers($tp1, $tp2);
?>

<div class="container">
<?php foreach($merged as $id => $row):
	$game = isset($row['game']) ? $row['game'] : array('id_game' => $id, 'time' => 0);
    $game = (object) $game;

	$players1 = isset($row['players1']) ? $row['players1'] : array();
	$players2 = isset($row['players2']) ? $row['players2'] : array();

?>
	<table class="table">
		<thead>
			<tr>
				<th colspan="2">
					game id = <?= $game->id_game;?><br/>
					time = <?= $game->time;?><br/>
                    deadline1 = <?= $game->deadline1;?><br/>
                    deadline2 = <?= $game->deadline2;?><br/>
				</th>
			</tr>
		</thead>
		<tbody>
		<?php for($i = 0; $i < max(count($players1), count($players2)); $i++):?>
			<tr>
				<td>
					<?php if(isset($players1[$i])):?>
						name = <?= $players1[$i]['name_player'];?><br>
						line = <?= $players1[$i]['line'];?>
					<?php endif;?>
				</td>
				<td>
					<?php if(isset($players2[$i])):?>
						name = <?= $players2[$i]['name_player'];?><br>
						line = <?= $players2[$i]['line'];?>
					<?php endif;?>
				</td>
			</tr>
		<?php endfor;?>
		</tbody>
	</table>
<?php endforeach;?>
</div>
