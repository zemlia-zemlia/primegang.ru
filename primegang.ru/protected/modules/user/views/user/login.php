<?php
$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Login");
$this->breadcrumbs=array(
	UserModule::t("Login"),
);
?>

<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			<h1><?php echo UserModule::t("Login"); ?></h1>
			<?php if(Yii::app()->user->hasFlash('loginMessage')): ?>
			<div class="success">
				<?php echo Yii::app()->user->getFlash('loginMessage'); ?>
			</div>
			<?php endif; ?>
			
			<p><?php echo UserModule::t("Please fill out the following form with your login credentials:"); ?></p>
			
			<div class="form">
			<?php echo CHtml::beginForm(); ?>
			
				<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
				
				<?php echo CHtml::errorSummary($model); ?>
				
				<div class="row">
					<?php echo CHtml::activeLabelEx($model,'username'); ?>
					<?php echo CHtml::activeTextField($model,'username') ?>
				</div>
				<div class="row">
					<?php echo CHtml::activeLabelEx($model,'password'); ?>
					<?php echo CHtml::activePasswordField($model,'password') ?>
				</div>
				<div class="row rememberMe">
					<?php echo CHtml::activeCheckBox($model,'rememberMe'); ?>
					<?php echo CHtml::activeLabelEx($model,'rememberMe'); ?>
				</div>
				<div class="row submit">
					<button class="btn btn-default" type="submit"><?php echo UserModule::t("Login")?></button>
				</div>
			<?php echo CHtml::endForm(); ?>
			</div><!-- form -->
			
			<?php
			$form = new CForm(array(
			    'elements'=>array(
			        'username'=>array(
			            'type'=>'text',
			            'maxlength'=>32,
			        ),
			        'password'=>array(
			            'type'=>'password',
			            'maxlength'=>32,
			        ),
			        'rememberMe'=>array(
			            'type'=>'checkbox',
			        )
			    ),
			
			    'buttons'=>array(
			        'login'=>array(
			            'type'=>'submit',
			            'label'=>'Login',
			        ),
			    ),
			), $model);
			?>
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