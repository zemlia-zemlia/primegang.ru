<?php
/* @var $this SudokuController */
$this->pageTitle = "Ваша команда";
?>
<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="league">
				<h1 class="league_title">Ваша команда</h1>
				<?php if(Yii::app()->user->isGuest):?>
					<a href="/user/login">Войдите</a> или <a href="/user/register">зарегистрируйтесь</a>, чтобы принять участие в турнире по судоку! 
				<?php else:
					$sudokuPlayer = SudokuTeamPlayers::model()->find("id_user=:id_user and active=1",array("id_user"=>Yii::app()->user->id));
					if(empty($sudokuPlayer)):
				?>
						<!--Пользователь не состоит в команде-->
						Вы не состоите ни в одной команде турнира VIVA SUDOKU. <br/>
						<a href="/sudoku/teamrequest">Отправить заявку на создание команды</a>
					<?php else:?>
						<!--Пользователь состоит в команде-->
						<?php if($sudokuPlayer->captain || $sudokuPlayer->vicecaptain):?>
							<!--Текущий игрок капитан или вице-капитан-->
							<?php $this->renderPartial('_teamAdmin',array('model'=>$model,'sudokuPlayer'=>$sudokuPlayer));?>
						<?php else:?>
							<!--Текущий игрок не капитан-->
							<?php $this->renderPartial('_teamView',array('sudokuPlayer'=>$sudokuPlayer));?>
						<?php endif;?>
						
						<!--активность команды-->
						<?php if(!$sudokuPlayer->team->active):?>
							<p class="bg-warning">Команда не допущена к участию в турнире!</p>
							<?php
								$comment = strip_tags("".$sudokuPlayer->team->comment);
								if(!empty($comment)):?>
								<p><b>Комментарий администратора:</b><br/><?php echo $sudokuPlayer->team->comment;?></p>
							<?php else:?>
								<p>Возможно, это потому, что администратор сайта еще не проверил команду. Подождите некоторое время.</p>
							<?php endif;?>
						<?php endif;?>
						<!--конец активность команды-->
					<?php endif;?>
				<?php endif;?>
			</div>
		</div>
		<div class="col-lg-6 right">
		</div>
	</div>
</div>