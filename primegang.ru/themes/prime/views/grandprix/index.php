<?php
/* @var $this GranpriController */
$this->pageTitle = "Гран-При";
?>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<h1 class="league_title">Гран-При</h1>
			<!--полная статистика по сезону-->
			<?php
				if(!empty($stats)): 
			?>
			<div class="league_statistics grand-prix">
				
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
					<?php foreach($stats as $key=>$stat):?>
						<tr class="<?php
						echo (isset($stat->user) && $stat->user->id == Yii::app()->user->id) ? "active" : "";
						?>">
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
			<?php else:?>
				Статистики по сыгранным турам пока нет!
			<?php endif;?>
		</div>			
		<!--<div class="col-lg-6 right">
			<div class="league_sidebar">
				<?php $this->widget('Statistics', array(
					'type'	=>"season",
					'dataId'=> "",
					'limit' => 3,
					'view' 	=> "_sidebar",
				)); ?>
			</div>
		-->
		</div>
	</div>
</div>
