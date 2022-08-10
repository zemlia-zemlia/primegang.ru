<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Jquery -->
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery/jquery-2.1.1.min.js"></script>

	<!-- Бутстрап -->
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap/bootstrap.min.css">
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bootstrap/bootstrap.min.js"></script>
	
	<!-- Тинимсе -->
	<script type="text/javascript" src="/js/tinymce/js/tinymce/tinymce.min.js"></script>
	<script type="text/javascript" src="/js/admin/admin.js"></script>

	<!--лайтбокс-->
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/lightbox/css/lightbox.css">
	<script src="<?php echo Yii::app()->request->baseUrl; ?>/lightbox/js/lightbox.min.js"></script>
	
	<!-- Файлы темы -->
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/main.css">
	<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/admin/sticky.footer.css">

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

	<nav class="navbar navbar-default" role="navigation">
		<div class="container">
			<!-- Название компании и кнопка, которая отображается для мобильных устройств группируются для лучшего отображения при свертывание -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Включить навигацию</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<!-- Группируем ссылки, формы, выпадающее меню и прочие элементы -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<?php $this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array('label'=>'Рабочий стол',	'url'=>array('/admin/index'), 			'active'=>(Yii::app()->controller->id=='default')),
					),
					'htmlOptions'=>array('class'=>'nav navbar-nav'),
				)); ?>
				<ul class="nav navbar-nav">
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Информационные блоки <b class="caret"></b></a>
						<?php $this->widget('zii.widgets.CMenu',array(
							'items'=>array(
								array('label'=>'Новости', 		'url'=>array('/admin/news'), 	'active'=>(Yii::app()->controller->id=='news')),
								array('label'=>'Страницы', 		'url'=>array('/admin/pages'), 	'active'=>(Yii::app()->controller->id=='pages')),
							),
							'htmlOptions'=>array('class'=>'dropdown-menu'),
						)); ?>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Опердеятельность <b class="caret"></b></a>
						<?php $this->widget('zii.widgets.CMenu',array(
							'items'=>array(
								array('label'=>'Лиги', 		'url'=>array('/admin/leagues'), 	'active'=>(Yii::app()->controller->id=='leagues')),
								array('label'=>'Команды', 	'url'=>array('/admin/teams'), 		'active'=>(Yii::app()->controller->id=='teams')),
								array('label'=>'Сезоны', 	'url'=>array('/admin/seasons'), 	'active'=>(Yii::app()->controller->id=='seasons')),
								array('label'=>'Туры', 		'url'=>array('/admin/tours'), 		'active'=>(Yii::app()->controller->id=='tours')),
								array('label'=>'Игры', 		'url'=>array('/admin/games'), 		'active'=>(Yii::app()->controller->id=='games')),
								array('label'=>'Прогнозы',	'url'=>array('/admin/prognosis'), 	'active'=>(Yii::app()->controller->id=='prognosis')),
							),
							'htmlOptions'=>array('class'=>'dropdown-menu'),
						)); ?>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Viva Sudoku <b class="caret"></b></a>
						<?php $this->widget('zii.widgets.CMenu',array(
							'items'=>array(
								array('label'=>'Команды игроков', 	'url'=>array('/admin/sudokuteams'), 'active'=>(Yii::app()->controller->id=='sudokuteams')),
								array('label'=>'Туры', 				'url'=>array('/admin/sudokutours'), 'active'=>(Yii::app()->controller->id=='sudokutours')),
								array('label'=>'Сезоны судоку',		'url'=>array('/admin/sudokuseasons'), 'active'=>(Yii::app()->controller->id=='sudokuseasons')),
								array('label'=>'Редактировать турнирную таблицу',		'url'=>array('/admin/sudokuseasons/table_edit'),
                                    'active'=>(Yii::app()->controller->id=='sudokuseasons' && Yii::app()->controller->action =='tableEdit')),
							),
							'htmlOptions'=>array('class'=>'dropdown-menu'),
						)); ?>
					</li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Служебные <b class="caret"></b></a>
						<?php $this->widget('zii.widgets.CMenu',array(
							'items'=>array(
								array('label'=>'Верхнее меню', 	'url'=>array('/admin/topmenu'), 	'active'=>(Yii::app()->controller->id=='topmenu')),
								array('label'=>'Пользователи', 	'url'=>array('/user/admin'), 		'active'=>(Yii::app()->controller->id=='admin')),
							),
							'htmlOptions'=>array('class'=>'dropdown-menu'),
						)); ?>
					</li>
				</ul>
				<?php $this->widget('zii.widgets.CMenu',array(
					'items'=>array(
						array('label'=>'Войти', 'url'=>array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
						array('label'=>'Выйти ('.Yii::app()->user->name.')', 'url'=>array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
					),
					'htmlOptions'=>array('class'=>'nav navbar-nav navbar-right'),
				)); ?>
			</div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>
	
	<div class="container" id="page">
		<div class="row">
		<?php if(isset($this->breadcrumbs)):?>
			<?php $this->widget('zii.widgets.CBreadcrumbs', array(
				'links'=>$this->breadcrumbs,
			)); ?><!-- breadcrumbs -->
		<?php endif?>
		</div>
		<div class="row">
		<?php echo $content; ?>
		</div>
	</div><!-- page -->

<footer class="footer">
	<div class="container">
	Система администрирования разработана &copy; <?php echo date('Y'); ?> компанией <a href="http://macrodigital.ru">Macrodigital</a>.<br/>
	<?php echo Yii::powered(); ?>
	</div>
</footer><!-- footer -->

</body>
</html>
<