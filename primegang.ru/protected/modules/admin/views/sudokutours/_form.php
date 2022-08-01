<?php
/* @var $this SudokutoursController */
/* @var $model SudokuTours */
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
		$("button.select").click(function(){
			var _url = $(this).attr("data-href");
			var _datacolumn = $(this).attr("data-column");
			
			$.ajax({
				'type':"GET",
				'url':_url,
				'success':function(data) {
					$("#selectModal div.modal-body").html(data);
					initSelector("#selectModal div.modal-body", _datacolumn);
					
					$("#selectModal").modal('show');
				}
			});
		});
	});
</script>	
	

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'sudoku-tours-form',
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
		<?php echo $form->labelEx($model,'id_season'); ?>
		<?php echo $form->hiddenField($model,'id_season',array('data-column'=>'id_season')); ?>
		<button class="btn btn-default btn-block select" 
			id="id_season_select" type="button" 
			data-column="id_season"
			data-href="/admin/sudokuseasons/select"><?php
			if(empty($model->season)) echo "Выберите значение";
			else echo $model->season->name; 
		?></button>
		<?php echo $form->error($model,'id_season'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'tour_number'); ?>
		<?php echo $form->textField($model,'tour_number',array('class'=>'form-control','data-column'=>'tour_number')); ?>
		<?php echo $form->error($model,'tour_number'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'date'); ?>
		<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
		    $this->widget('CJuiDateTimePicker',array(
		        'model'=>$model, //Model object
		        'attribute'=>'date', //attribute name
		        'mode'=>'datetime', //use "time","date" or "datetime" (default)
		        'htmlOptions'=>array('class'=>'form-control'),
		        'language'=>'ru',
		        'options'=>array() // jquery plugin options
		    ));
		?>					
		<?php echo $form->error($model,'date'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'date_cap'); ?>
		<?php Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
		    $this->widget('CJuiDateTimePicker',array(
		        'model'=>$model, //Model object
		        'attribute'=>'date_cap', //attribute name
		        'mode'=>'datetime', //use "time","date" or "datetime" (default)
		        'htmlOptions'=>array('class'=>'form-control'),
		        'language'=>'ru',
		        'options'=>array() // jquery plugin options
		    ));
		?>					
		<?php echo $form->error($model,'date_cap'); ?>
	</div>
	<div class="well games">
		<h4>Матчи</h4>
		<!--матчи, на которые будут делаться прогнозы-->
		<?php $this->renderPartial('_gamesForm',array('form'=>$form,'model'=>$model));?>
	</div>
	<div class="well teams">
		<h4>Играющие команды</h4>
		<!-- команды, играющие в паре -->
		<?php $this->renderPartial('_teamsForm',array('form'=>$form,'model'=>$model));?>
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