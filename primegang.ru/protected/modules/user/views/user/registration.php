<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Registration");
$this->breadcrumbs=array(
	UserModule::t("Registration"),
);
?>
<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			
			<h1><?php echo UserModule::t("Registration"); ?></h1>
			
			<?php if(Yii::app()->user->hasFlash('registration')): ?>
			<div class="success">
			<?php echo Yii::app()->user->getFlash('registration'); ?>
			</div>
			<?php else: ?>
			
			<div class="form">
			<?php $form=$this->beginWidget('UActiveForm', array(
				'id'=>'registration-form',
				'enableAjaxValidation'=>true,
				'disableAjaxValidationAttributes'=>array('RegistrationForm_verifyCode'),
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
				'htmlOptions' => array('enctype'=>'multipart/form-data'),
			)); ?>
			
				<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
				
				<?php echo $form->errorSummary(array($model,$profile)); ?>
				
				<div class="row">
				<?php echo $form->labelEx($model,'username'); ?>
				<?php echo $form->textField($model,'username'); ?>
				<?php echo $form->error($model,'username'); ?>
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
				
				<div class="row">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email'); ?>
				<?php echo $form->error($model,'email'); ?>
				</div>
				
			<?php 
					$profileFields=$profile->getFields();
					if ($profileFields) {
						foreach($profileFields as $field) {
						?>
				<div class="row">
					<?php echo $form->labelEx($profile,$field->varname); ?>
					<?php 
					if ($widgetEdit = $field->widgetEdit($profile)) {
						echo $widgetEdit;
					} elseif ($field->range) {
						echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
					} elseif ($field->field_type=="TEXT") {
						echo$form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
					} else {
						echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
					}
					 ?>
					<?php echo $form->error($profile,$field->varname); ?>
				</div>	
						<?php
						}
					}
			?>
				<?php if (UserModule::doCaptcha('registration')): ?>
				<div class="row">
					<?php echo $form->labelEx($model,'verifyCode'); ?>
					
					<?php $this->widget('CCaptcha'); ?>
					<?php echo $form->textField($model,'verifyCode'); ?>
					<?php echo $form->error($model,'verifyCode'); ?>
					
					<p class="hint"><?php echo UserModule::t("Please enter the letters as they are shown in the image above."); ?>
					<br/><?php echo UserModule::t("Letters are not case-sensitive."); ?></p>
				</div>
				<?php endif; ?>
				
				<div class="row submit">
					<button class="btn btn-default submit-btn" type="submit" disabled><?php echo UserModule::t("Register")?></button>
				</div>
				<input class="submit-tkn" type="hidden" name="token" value="">
<script src="https://www.google.com/recaptcha/api.js?render=6Lc4kH0UAAAAALmCN36bK9fY9FB-Pj4zhrgGXazW"></script>
<script>
grecaptcha.ready(function(){
	grecaptcha.execute('6Lc4kH0UAAAAALmCN36bK9fY9FB-Pj4zhrgGXazW',{action:'registration'}).then(function(token){
		document.querySelector('.submit-tkn').value = token
		document.querySelector('.submit-btn').removeAttribute('disabled')
	})
})
</script>
			
			<?php $this->endWidget(); ?>
			</div><!-- form -->
			<?php endif; ?>
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