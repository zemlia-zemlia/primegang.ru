<?php 
$this->pageTitle = "Заявка на участие в турнире VIVA SUDOKU!";
?>
<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<div class="league">
				<h1 class="league_title">Заявка на участие в турнире VIVA SUDOKU!</h1>
			<?php if(Yii::app()->user->isGuest):?>
				<a href="/user/login">Войдите</a> или <a href="/user/register">зарегистрируйтесь</a>, чтобы подать заявку! 
			<?php else:
				$sudokuPlayer = SudokuTeamPlayers::model()->find("id_user=:id_user and active=1",array("id_user"=>Yii::app()->user->getId()));
				if(!empty($sudokuPlayer)):
			?>
					<!--Пользователь уже состоит в команде-->
					Вы уже состоите в команде <a href="/sudoku/team"><?php echo $sudokuPlayer->team->name?></a>
					под псевдонимом <?php echo $sudokuPlayer->name;?>.<br/>
					<?php if($sudokuPlayer->captain):?>
						Распустить команду или сменить капитана можно на <a href="/sudoku/team">ее странице</a>.
					<?php else:?>
						Чтобы выйти из команды, обратитесь к своему капитану или администратору сайта.
					<?php endif;?>
				<?php else:?>
					<!--Пользователь не состоит в команде и может подать заявку-->
					<?php if(Yii::app()->user->hasFlash('teamrequest')): ?>
					<div class="flash-success">
						<?php echo Yii::app()->user->getFlash('teamrequest'); ?>
					</div>
					<?php else: ?>
						<p>
							Заполните заявку на команду с помощью формы ниже:
						</p>
						
						<div class="form">
						
						<?php $form=$this->beginWidget('CActiveForm', array(
							'id'=>'teamrequest-form',
							'enableClientValidation'=>true,
							'clientOptions'=>array(
								'validateOnSubmit'=>true,
							),
						)); ?>
						
							<p class="note">Поля, отмеченные <span class="required">*</span>, обязательны.</p>
						
							<?php echo $form->errorSummary($model); ?>
							
							<div class="form-group">
								<?php echo $form->labelEx($model,'teamname'); ?>
								<?php echo $form->textField($model,'teamname',array('class'=>"form-control")); ?>
								<?php echo $form->error($model,'teamname'); ?>
							</div>
							
							<table class="table">
								<thead>
									<tr>
										<th style="text-align: center;">Игрок</th>
										<th>Логин</th>
										<th>Псевдоним</th>
									</tr>
								</thead>
								<tbody>
								<?php
									$_sfields = "captain,player1,player2,player3,player4,player5";
									$fields = explode(",", $_sfields);
									foreach($fields as $key=>$f):
								?>
									<tr>
										<td><?php echo $form->labelEx($model,$f.'login'); ?></td>
										<td>
											<?php 
												$htmlOptions = array('class'=>"form-control");
												if($f=="captain") $htmlOptions['value'] = Yii::app()->user->username;
												echo $form->textField($model,$f.'login',$htmlOptions); ?>
											<?php echo $form->error($model,$f.'login'); ?>
										</td>
										<td>
											<?php echo $form->textField($model,$f.'name',array('class'=>"form-control")); ?>
											<?php echo $form->error($model,$f.'name'); ?>
										</td>
									</tr>
								<?php endforeach; ?>
								</tbody>
							</table>
						
							<div class="row buttons">
								<button class="btn btn-primary" type="submit">Отправить</button>
							</div>
						
						<?php $this->endWidget(); ?>
						
						</div><!-- form -->
						
					<?php endif; ?>
				<?php endif;?>
			<?php endif;?>
		</div>
		</div>
		<div class="col-lg-6 right">
			<?php $this->widget('Statistics', array(
				'type'	=>"season",
				'dataId'=> "",
				'limit' => 10,
				'view' 	=> "_sidebar",
			)); ?>
		</div>
	</div>
</div>