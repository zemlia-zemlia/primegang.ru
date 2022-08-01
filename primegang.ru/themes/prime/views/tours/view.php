<?php
/* @var $this ToursController */
/* @var $model Tours */

$this->breadcrumbs=array(
	'Tours'=>array('index'),
	$model->id,
);

?>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">

			<h1><?php echo $model->name; ?></h1>
			
			<p>Лига: <?php echo $model->league->name;?></p>
			<p>Сезон: <?php echo $model->season->name;?></p>
			<p>Номер тура: <?php echo $model->tour_number;?></p>
			<p>Дата начала тура: <?php echo $model->date;?></p>
			
			<!--игры в туре-->
			<?php foreach($model->games as $game):?>
				<p>Название: <?php echo $game->name;?></p>
				<p>Команда 1: <?php echo $game->team1->name;?></p>
				<p>Команда 2: <?php echo $game->team2->name;?></p>
				<p>Дата игры: <?php echo $game->date;?></p>
				<?php if($game->ready):?>
					<!--игра состоялась-->
					<p>Счет команды 1: <?php echo $game->score_team1_total;?></p>
					<p>Счет команды 2: <?php echo $game->score_team2_total;?></p>
				<?php endif;?>
			<?php endforeach;?>
			
			<!--очки, набранные за тур-->
			<?php
				$tourStats = $model->getTourStats();
				foreach($tourStats as $user):
			?>
				Аватар:
				<?php if(empty($user->profile->userimage)):?><img src="/images/site/no_avatar.png">
				<?php else:?><img src="/<?php echo $user->profile->userimage;?>"><?php endif;?>
				<br/>
				Логин: <?php echo $user->username;?><br/>
				Очки: <?php echo $user->balls;?>
			<?php endforeach; ?>
		</div>
		<div class="col-lg-6 right">
			<?php $this->widget('Statistics', array(
				'type'	=>"league",
				'dataId'=> $model->id_league,
				'limit' => 10,
				'view' 	=> "_sidebar",
			)); ?>
		</div>
	</div>
</div>
