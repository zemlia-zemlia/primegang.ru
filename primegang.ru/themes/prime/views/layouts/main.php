<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="/favicon.png" />
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
	
	<!-- Jquery -->
	<script src="//code.jquery.com/jquery-latest.js"></script>
	<!--<script type="text/javascript" src="/js/blocksit.js"></script>-->
	
	<!-- Featherlight
	<link href="//cdn.rawgit.com/noelboss/featherlight/1.2.3/release/featherlight.min.css" type="text/css" rel="stylesheet" title="Featherlight Styles" />
	<script src="//cdn.rawgit.com/noelboss/featherlight/1.2.3/release/featherlight.min.js" type="text/javascript" charset="utf-8"></script>
	 -->
  
	<!-- Бутстрап -->
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/bootstrap-theme.min.css">
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap/bootstrap.min.js"></script>

	<!-- site init-->
	<script type="text/javascript" src="/js/site_init.js"></script>
	
	<!-- Файлы темы -->
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/prime/main.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/prime/sticky.footer.css">

	<title><?php echo CHtml::encode($this->pageTitle); 
		if(Yii::app()->name <> $this->pageTitle) {
			echo " - ";
			echo Yii::app()->name;
		}
		?></title>


</head>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter30882546 = new Ya.Metrika({id:30882546,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/30882546" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<body>

<!-- BEGIN CBOX - www.cbox.ws - v4.3
<div id="cboxdiv" style="position: relative; margin: 0 auto; width: 200px; font-size: 0; line-height: 0;">
<div style="position: relative; height: 272px; overflow: auto; overflow-y: auto; -webkit-overflow-scrolling: touch; border: 0px solid;"><iframe src="http://www3.cbox.ws/box/?boxid=3468955&boxtag=6e1498&sec=main" marginheight="0" marginwidth="0" frameborder="0" width="100%" height="100%" scrolling="auto" allowtransparency="yes" name="cboxmain3-3468955" id="cboxmain3-3468955"></iframe></div>
<div style="position: relative; height: 108px; overflow: hidden; border: 0px solid; border-top: 0px;"><iframe src="http://www3.cbox.ws/box/?boxid=3468955&boxtag=6e1498&sec=form" marginheight="0" marginwidth="0" frameborder="0" width="100%" height="100%" scrolling="no" allowtransparency="yes" name="cboxform3-3468955" id="cboxform3-3468955"></iframe></div>
</div>
 END CBOX -->	
	<?php
		//определим _mId и _cId
		$_mId = (!isset(Yii::app()->controller->module) || empty(Yii::app()->controller->module)) ? "" : Yii::app()->controller->module->id;
		$_cId = Yii::app()->controller->id;
	?>
	<header>
		<div class="row top">
			<div class="container">
				<div class="col-lg-5 logo">
					<a href="/"><img src="/images/site/logo_top.png"></a>
				</div>
				<div class="col-lg-12 nav">
					<div class="btn-group btn-group-justified" role="group" aria-label="Меню">
						<?php
							$menu = TopMenu::model()->findAll();
							foreach($menu as $item):
//                                CVarDumper::dump($menu,5,true); die;
								$itemClass = str_replace('/', '', $item->url);
//								$itemClass = array_pop(explode('/', $item->url));
								if(strtolower(substr($item->url, 0, 4)) == 'http') $itemClass="external";
						?>
						<a role="button" class="btn btn-link <?php echo $itemClass;?>" href="<?php echo $item->url;?>" <?php
							if(strtolower(substr($item->url, 0, 4)) == 'http') echo "target='_blank'"; 
						?>>
							<span class="name"><?php echo $item->name;?></span>
						</a>
						<?php endforeach;?>
					</div>				
				</div>
				<div class="col-lg-1"></div>
				<div class="col-lg-3 login">
					<?php if(Yii::app()->user->isGuest):?>
						<form action="/user/login" method="POST" >
							<input type="hidden"	 name="UserLogin[rememberMe]" value="1" />
							<input type="text"		 name="UserLogin[username]" class="form-control" placeholder="Логин или email" />
							<input type="password"	 name="UserLogin[password]" class="form-control" placeholder="Пароль" />
							<button type="submit"	 class="btn btn-default">Войти</button>  
						</form>
					<?php endif;?>
				</div>
				<div class="col-lg-3 userlinks">
					<?php if(Yii::app()->user->isGuest):?>
					<p class="reg"><a href="/user/registration">Регистрация</a></p>
					<p class="recover"><a href="/user/recovery">Напомнить пароль</a></p>
					<?php else:?>
					<p class="m-t">
						<?php 
							$currentUser = User::model()->findByPk(Yii::app()->user->id);
							if(!empty($currentUser) && $currentUser->superuser):						
						?><a href="/admin" class="admin">&nbsp;</a><?php endif;?>
						<?php if(!empty($currentUser)):?>
						<a href="/user/profile" class="user"><?php echo $currentUser->display_name;?></a>
						<?php endif;?>
					</p>
					<p class="logout"><a href="/user/logout">Выйти</a></p>
					<?php endif;?>
				</div>
			</div>
		</div>
		<div class="row leagues">
			<div class="container">
				<div class="leagues col-lg-24">
					<div class="btn-group btn-group-justified" role="group" aria-label="Меню">
					<?php
						$leagues = Leagues::model()->findAll();
						foreach($leagues as $league):
					?>
						<a role="button" class="btn btn-link <?php echo $league->alias;?>" href="/leagues/<?php echo $league->alias;?>">
							<span class="logo">&nbsp;</span>
							<span class="flag">&nbsp;</span>
							<?php $exploded = explode(" ",$league->name); ?>
							<em><?php echo array_shift($exploded);?></em>
							<?php echo implode("&nbsp;",$exploded);?>
						</a>
					<?php endforeach;?>
						<a role="button" class="btn btn-link sudoku" href="/sudoku">
							<span class="logo">&nbsp;</span>
							<span class="flag">&nbsp;</span>
							<em>VIVA</em>
							Sudoku!
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row filter" style="display:none;">
			<div class="container">
				<div class="gap col-lg-6">&nbsp;</div>
				<div class="filters col-lg-18">
					<a href="/filter/all" class="all">
						<span class="icon">&nbsp;</span>
						Все турниры
					</a>
					<a href="/filter/granpri" class="granpri">
						<span class="icon">&nbsp;</span>
						Гран-при
					</a>
				</div>
			</div>
		</div>
	</header>
	
	<?php echo $content;?>
	
	<div class="row footer">
		<div class="container">
			<div class="col-lg-18 primegang">
				<a class="logo" href="/"><img src="/images/site/logo_top.png"></a>
			</div>
			<div class="col-lg-6 macrologo">
				<span>Сделано в</span>
				<a class="logo" href="http://macrodigital.ru" target="_blank"><img src="/images/site/macrologo.png"></a>
			</div>
		</div>
	</div>

</body>
</html>