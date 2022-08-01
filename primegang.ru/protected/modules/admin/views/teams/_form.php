<?php
/* @var $this TeamsController */
/* @var $model Teams */
/* @var $form CActiveForm */
?>

<!-- relation scripts -->
<script>
var initSelector = function(list_selector,dataColumn) {
	$(list_selector).find(".option").click(function(){
		var _option = $(this);
		var _dataId = _option.attr("data-id");
		$("input[data-column="+dataColumn+"]").attr("value",_dataId);
		$("button[data-column="+dataColumn+"]").html(_option.html());
		$("#selectModal").modal('hide');
		return false;
	});
}
$(function(){
		$("button#id_league_select").click(function(){
		$.ajax({
			'type':"GET",
			'url':"/admin/leagues/select",
			'success':function(data) {
				$("#selectModal div.modal-body").html(data);
				initSelector("#selectModal div.modal-body", "id_league");
				
				$("#selectModal").modal('show');
			}
		});
	});
	});
</script>	
	

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'teams-form',
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
		<?php echo $form->labelEx($model,'id_league'); ?>
		<?php ?>
				
				<?php echo $form->hiddenField($model,'id_league',array('data-column'=>'id_league')); ?>
				<button class="btn btn-default btn-block" id="id_league_select" type="button" data-column="id_league"><?php
					if(empty($model->league)) echo "Выберите значение";
					else echo $model->league->name; 
				?></button>
				
				<?php echo ""; ?>
		<?php echo $form->error($model,'id_league'); ?>
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
		<?php echo $form->labelEx($model,'town'); ?>
		<?php echo $form->textField($model,'town',array('size'=>60,'maxlength'=>255,'class'=>'form-control','data-column'=>'town')); ?>
		<?php echo $form->error($model,'town'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'image_url'); ?>
		<?php ?>
			
					<div class="image_preview" id="preview_Teams_image_url">
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
					      	<iframe src="/filemanager/dialog.php?type=1&field_id=Teams_image_url"></iframe>
					      </div>
					    </div>
					  </div>
					</div>	
							<?php echo ""; ?>
		<?php echo $form->error($model,'image_url'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50, 'class'=>'editor')); ?>
		<?php echo $form->error($model,'text'); ?>
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