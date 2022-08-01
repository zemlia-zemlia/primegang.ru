<?php
/* @var $this PrognosisController */
/* @var $model Prognosis */
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
		$("button#id_user_select").click(function(){
		$.ajax({
			'type':"GET",
			'url':"/admin/user/select",
			'success':function(data) {
				$("#selectModal div.modal-body").html(data);
				initSelector("#selectModal div.modal-body", "id_user");
				
				$("#selectModal").modal('show');
			}
		});
	});
		$("button#id_game_select").click(function(){
		$.ajax({
			'type':"GET",
			'url':"/admin/games/select",
			'success':function(data) {
				$("#selectModal div.modal-body").html(data);
				initSelector("#selectModal div.modal-body", "id_game");
				
				$("#selectModal").modal('show');
			}
		});
	});
	});
</script>	
	

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'prognosis-form',
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
		<?php echo $form->labelEx($model,'id_user'); ?>
		<?php ?>
				
				<?php echo $form->hiddenField($model,'id_user',array('data-column'=>'id_user')); ?>
				<button class="btn btn-default btn-block" id="id_user_select" type="button" data-column="id_user"><?php
					if(empty($model->user)) echo "Выберите значение";
					else echo $model->user->username; 
				?></button>
				
				<?php echo ""; ?>
		<?php echo $form->error($model,'id_user'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'id_game'); ?>
		<?php ?>
				
				<?php echo $form->hiddenField($model,'id_game',array('data-column'=>'id_game')); ?>
				<button class="btn btn-default btn-block" id="id_game_select" type="button" data-column="id_game"><?php
					if(empty($model->game)) echo "Выберите значение";
					else echo $model->game->name; 
				?></button>
				
				<?php echo ""; ?>
		<?php echo $form->error($model,'id_game'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'score_team1_total'); ?>
		<?php echo $form->textField($model,'score_team1_total',array('class'=>'form-control','data-column'=>'score_team1_total')); ?>
		<?php echo $form->error($model,'score_team1_total'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'score_team2_total'); ?>
		<?php echo $form->textField($model,'score_team2_total',array('class'=>'form-control','data-column'=>'score_team2_total')); ?>
		<?php echo $form->error($model,'score_team2_total'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'computed'); ?>
		<?php echo $form->textField($model,'computed',array('class'=>'form-control','data-column'=>'computed')); ?>
		<?php echo $form->error($model,'computed'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'balls'); ?>
		<?php echo $form->textField($model,'balls',array('class'=>'form-control','data-column'=>'balls')); ?>
		<?php echo $form->error($model,'balls'); ?>
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