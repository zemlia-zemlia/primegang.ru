<?php $this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Profile");
$this->breadcrumbs=array(
	UserModule::t("Profile")=>array('profile'),
	UserModule::t("Edit"),
);
$this->menu=array(
	((UserModule::isAdmin())
		?array('label'=>UserModule::t('Manage Users'), 'url'=>array('/user/admin'))
		:array()),
    array('label'=>UserModule::t('List User'), 'url'=>array('/user')),
    array('label'=>UserModule::t('Profile'), 'url'=>array('/user/profile')),
    array('label'=>UserModule::t('Change password'), 'url'=>array('changepassword')),
    array('label'=>UserModule::t('Logout'), 'url'=>array('/user/logout')),
);
?>
<div class="row content">
	<div class="container">
		<div class="col-lg-18 left">
			

			<h1><?php echo UserModule::t('Edit profile'); ?></h1>
			
			<?php if(Yii::app()->user->hasFlash('profileMessage')): ?>
			<div class="success">
			<?php echo Yii::app()->user->getFlash('profileMessage'); ?>
			</div>
			<?php endif; ?>
			<div class="form">
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'profile-form',
				'enableAjaxValidation'=>true,
				'htmlOptions' => array('enctype'=>'multipart/form-data'),
			)); ?>
			
				<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
			
				<?php echo $form->errorSummary(array($model,$profile)); ?>
			
			<?php 
					$profileFields=$profile->getFields();
					if ($profileFields) {
						foreach($profileFields as $field) {
						?>
				<div class="row">
					<?php echo $form->labelEx($profile,$field->varname);
					
					if ($widgetEdit = $field->widgetEdit($profile)) {
						echo $widgetEdit;
					} elseif ($field->range) {
						echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
					} elseif ($field->field_type=="TEXT") {
						echo $form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
					} else {
						echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
					}
					echo $form->error($profile,$field->varname); ?>
				</div>	
						<?php
						}
					}
			?>
				<div class="row">
					<?php echo $form->labelEx($model,'username'); ?>
					<?php echo $form->textField($model,'username',array('size'=>20,'maxlength'=>20)); ?>
					<?php echo $form->error($model,'username'); ?>
				</div>
			
				<div class="row">
					<?php echo $form->labelEx($model,'email'); ?>
					<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>128)); ?>
					<?php echo $form->error($model,'email'); ?>
				</div>
			
				<div class="row buttons">
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
					'limit' => 3,
					'view' 	=> "_sidebar",
				)); ?>
			</div>
		</div>
	</div>
</div>	