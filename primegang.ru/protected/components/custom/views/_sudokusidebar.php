<span style="display:none;"><?php $statistics = $this->getStatistics();?></span>
<?php if(!empty($statistics)):?>
<div class="top league <?php echo $type;?>">
	<h2><?php echo $widgetHeader?></h2>
	<?php foreach($statistics as $key=>$row):
		$user = User::model()->findByPk($row['id_user']);
	?>
		<div class="item">
			<?php if($key < 1):?><img src="/images/site/crown_league.png"><?php endif;?>
			<div class="avatar"><img src="/<?php
				if(!empty($user->profile->userimage)) echo $user->profile->userimage;
				else echo "images/site/no_avatar.png";
			?>"></div>
			<h5><?php echo $row['name_player'];?></h5>
			<h4><?php echo $user->display_name;?></h4>
			<h6><?php echo $row['points'];?></h6>
		</div>
	<?php endforeach;?>
	<a href="/sudoku/<?php echo $type; ?>?s=<?php echo $this->season;?>">Полная таблица</a>
</div>
<?php endif; ?>