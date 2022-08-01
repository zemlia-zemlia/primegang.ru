<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Change Password");
$this->breadcrumbs=array(
	UserModule::t("Profile") => array('/user/profile'),
	UserModule::t("Change Password"),
);
$this->menu=array(
	((UserModule::isAdmin())
		?array('label'=>UserModule::t('Manage Users'), 'url'=>array('/user/admin'))
		:array()),
    array('label'=>UserModule::t('List User'), 'url'=>array('/user')),
    array('label'=>UserModule::t('Profile'), 'url'=>array('/user/profile')),
    array('label'=>UserModule::t('Edit'), 'url'=>array('edit')),
    array('label'=>UserModule::t('Logout'), 'url'=>array('/user/logout')),
);
?>
<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			
			<h1><?php echo UserModule::t("Change password"); ?></h1>
			
			<div class="form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'changepassword-form',
				'enableAjaxValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
			)); ?>
			
				<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
				<?php echo $form->errorSummary($model); ?>
				
				<div class="row">
				<?php echo $form->labelEx($model,'oldPassword'); ?>
				<?php echo $form->passwordField($model,'oldPassword'); ?>
				<?php echo $form->error($model,'oldPassword'); ?>
				</div>
				
				<div class="row">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password'); ?>
				<?php echo $form->error($model,'password'); ?>
				<p class="hint">
				<?php echo UserModule::t("Minimal password length 4 symbols."); ?>
				</p>
				</div>
				
				<div class="row">
				<?php echo $form->labelEx($model,'verifyPassword'); ?>
				<?php echo $form->passwordField($model,'verifyPassword'); ?>
				<?php echo $form->error($model,'verifyPassword'); ?>
				</div>
				
				
				<div class="row submit">
				<button class="btn btn-default" type="submit"><?php echo UserModule::t("Save")?></button>
				</div>
			
			<?php $this->endWidget(); ?>
			</div><!-- form -->
		</div>			
		<div class="col-lg-6 right">
			<div class="sidebar">
				<?php $this->widget('Statistics', array(
					'type'	=>"season",
					'dataId'=> "",
					'limit' => 10,
					'view' 	=> "_sidebar",
				)); ?>
			</div>
		</div>
	</div>
</div>				