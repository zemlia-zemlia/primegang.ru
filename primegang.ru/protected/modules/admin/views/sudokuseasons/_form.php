<?php
/* @var $this SeasonsController */
/* @var $model Seasons */
/* @var $form CActiveForm */
?>

<!-- relation scripts -->
	

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'seasons-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Поля, отмеченные <span class="required">*</span>, обязательны.</p>

	<?php 
		$eS = "".$form->errorSummary($model);
		if (!empty($eS)) {?>
			<div class="panel panel-danger">
				<div class="panel-heading">Ошибки при заполнении формы</div>
				<div class="panel-body">
					<?php echo $eS; ?>
				</div>
			</div>
		<?}	
	?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255,'class'=>'form-control','data-column'=>'name')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'alias'); ?>
		<?php echo $form->textField($model,'alias',array('size'=>60,'maxlength'=>255,'class'=>'form-control','data-column'=>'alias')); ?>
		<?php echo $form->error($model,'alias'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'divisions'); ?>
		<?php echo $form->textField($model,'divisions',array('size'=>10,'maxlength'=>1,'class'=>'form-control','data-column'=>'divisions')); ?>
		<?php echo $form->error($model,'divisions'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'division_names'); ?>
		<?php echo $form->textField($model,'division_names',array('size'=>60,'maxlength'=>255,'class'=>'form-control','data-column'=>'division_names')); ?>
		<?php echo $form->error($model,'division_names'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'archive'); ?>
		<?php echo $form->checkBox($model,'archive'); ?>
		<?php echo $form->error($model,'archive'); ?>
	</div>

	<div class="form-group buttons">
		<button class="btn btn-primary" type="submit"><?php echo($model->isNewRecord ? 'Создать' : 'Сохранить'); ?></button>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->

<!-- Modal -->
<div class="modal fade" id="selectModal" tabindex="-1" role="dialog" aria-labelledby="selectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Выберите значение</h4>
      </div>
      <div class="modal-body"></div>
    </div>
  </div>
</div>