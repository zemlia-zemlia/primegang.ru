<?php
/* @var $this NewsController */
/* @var $model News */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'news-form',
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
		<?php echo $form->labelEx($model,'date'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiDatePicker', array(
				   'name' => 'date',
				   'model' => $model,
				   'attribute' => 'date',
				   'language' => 'ru',
				   'htmlOptions'=>array('class'=>'form-control'),
				   'options' => array('showAnim' => 'fold'),
				)); ?>
		<?php echo $form->error($model,'date'); ?>
	</div>

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
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50, 'class'=>'editor')); ?>
		<?php echo $form->error($model,'text'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'image_url'); ?>
		<?php ?>

		<div class="image_preview" id="preview_News_image_url">
		<?php if (!empty($model->image_url)):?>
			<img src="<?php echo $model->image_url;?>" class="img-rounded">
		<?php else: ?>
			<img src="/images/admin/no-picture.png" class="img-rounded">
		<?php endif; ?>
		</div>
						
		
	    <div class="input-group">
	      <span class="input-group-btn">
	        <button class="btn btn-default" data-toggle="modal" data-target="#imageModal" type="button">Обзор картинок</button>
	      </span>
	      <?php echo $form->textField($model,'image_url',array('class'=>'form-control','data-column'=>'image_url','placeholder'=>'Url картинки...')); ?>
	    </div><!-- /input-group -->
		
		<!-- Modal -->
		<div class="modal fade bs-example-modal-lg" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
		  <div class="modal-dialog modal-lg">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title" id="imageModalLabel">Изображения</h4>
		      </div>
		      <div class="modal-body">
		      	<iframe src="/filemanager/dialog.php?type=1&field_id=News_image_url"></iframe>
		      </div>
		    </div>
		  </div>
		</div>	
				<?php echo ""; ?>
		<?php echo $form->error($model,'image_url'); ?>
	</div>

	<div class="form-group buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->