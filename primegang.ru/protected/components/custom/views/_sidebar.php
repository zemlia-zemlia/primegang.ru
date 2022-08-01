<?php if(!empty($statistics)):?>
<div class="top league">
	<h2><?php echo $widgetHeader?></h2>
	<?php foreach($statistics as $key=>$user):?>
		<div class="item">
			<?php if($key < 1):?><img src="/images/site/crown_league.png"><?php endif;?>
			<div class="avatar"><img src="/<?php
				if(!empty($user->profile->userimage)) echo $user->profile->userimage;
				else echo "images/site/no_avatar.png";
			?>"></div>
			<h5><?php echo $user->display_name;?></h5>
			<h6><?php echo $user->balls;?></h6>
		</div>
	<?php endforeach;?>
</div>
<?php endif; ?>