<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
$this->pageTitle = "Логин";

?>

<div class="col-lg-14">
	<div class="content-header">Логин</div>
	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>
	
		<p class="note">Поля, отмеченные <span class="required">*</span>, обязательны.</p>
	
		<div class="form-group">
			<?php echo $form->labelEx($model,'username'); ?>
			<?php echo $form->textField($model,'username',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'username'); ?>
		</div>
	
		<div class="form-group">
			<?php echo $form->labelEx($model,'password'); ?>
			<?php echo $form->passwordField($model,'password',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'password'); ?>
		</div>
	
		<div class="form-group rememberMe">
			<?php echo $form->checkBox($model,'rememberMe'); ?>
			<?php echo $form->label($model,'rememberMe'); ?>
			<?php echo $form->error($model,'rememberMe'); ?>
		</div>
	
		<div class="form-group buttons">
			<?php echo CHtml::submitButton('Войти'); ?>
		</div>
	
	<?php $this->endWidget(); ?>
	</div><!-- form -->
</div>