<?php
/* @var $this GamesController */
/* @var $model Games */
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
		$("button#id_season_select").click(function(){
		$.ajax({
			'type':"GET",
			'url':"/admin/seasons/select",
			'success':function(data) {
				$("#selectModal div.modal-body").html(data);
				initSelector("#selectModal div.modal-body", "id_season");
				
				$("#selectModal").modal('show');
			}
		});
	});
		$("button#id_tour_select").click(function(){
			//выбираем тур в пределах выбранной лиги и сезона
			var _id_league = $("input[type=hidden][data-column=id_league]").val();
			var _id_season = $("input[type=hidden][data-column=id_season]").val();
		$.ajax({
			'type':"GET",
			'url':"/admin/tours/select?Tours[id_league]="+_id_league+"&Tours[id_season]="+_id_season,
			'success':function(data) {
				$("#selectModal div.modal-body").html(data);
				initSelector("#selectModal div.modal-body", "id_tour");
				
				$("#selectModal").modal('show');
			}
		});
	});
		$("button#id_team1_select").click(function(){
			var _id_league = $("input[type=hidden][data-column=id_league]").val();
		$.ajax({
			'type':"GET",
			'url':"/admin/teams/select?Teams[id_league]="+_id_league,
			'success':function(data) {
				$("#selectModal div.modal-body").html(data);
				initSelector("#selectModal div.modal-body", "id_team1");
				
				$("#selectModal").modal('show');
			}
		});
	});
		$("button#id_team2_select").click(function(){
			var _id_league = $("input[type=hidden][data-column=id_league]").val();
			$.ajax({
				'type':"GET",
				'url':"/admin/teams/select?Teams[id_league]="+_id_league,
				'success':function(data) {
					$("#selectModal div.modal-body").html(data);
					initSelector("#selectModal div.modal-body", "id_team2");
					
					$("#selectModal").modal('show');
				}
			});
		});
	});
</script>	
	

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'games-form',
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
		<?php echo $form->labelEx($model,'id_season'); ?>
		<?php ?>
				
				<?php echo $form->hiddenField($model,'id_season',array('data-column'=>'id_season')); ?>
				<button class="btn btn-default btn-block" id="id_season_select" type="button" data-column="id_season"><?php
					if(empty($model->season)) echo "Выберите значение";
					else echo $model->season->name; 
				?></button>
				
				<?php echo ""; ?>
		<?php echo $form->error($model,'id_season'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'id_tour'); ?>
		<?php ?>
				
				<?php echo $form->hiddenField($model,'id_tour',array('data-column'=>'id_tour')); ?>
				<button class="btn btn-default btn-block" id="id_tour_select" type="button" data-column="id_tour"><?php
					if(empty($model->tour)) echo "Выберите значение";
					else echo $model->tour->name; 
				?></button>
				
				<?php echo ""; ?>
		<?php echo $form->error($model,'id_tour'); ?>
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
	<div class="col-lg-12" style="padding-left:0; margin-left:0;">
		<div class="form-group">
			<?php echo $form->labelEx($model,'id_team1'); ?>
			<?php ?>
					
					<?php echo $form->hiddenField($model,'id_team1',array('data-column'=>'id_team1')); ?>
					<button class="btn btn-default btn-block" id="id_team1_select" type="button" data-column="id_team1"><?php
						if(empty($model->team1)) echo "Выберите значение";
						else echo $model->team1->name; 
					?></button>
					
					<?php echo ""; ?>
			<?php echo $form->error($model,'id_team1'); ?>
		</div>

		<div class="form-group">
			<?php echo $form->labelEx($model,'score_team1_total'); ?>
			<?php echo $form->textField($model,'score_team1_total',array('class'=>'form-control','data-column'=>'score_team1_total')); ?>
			<?php echo $form->error($model,'score_team1_total'); ?>
		</div>
	</div>
	<div class="col-lg-12" style="padding-right:0; margin-right:0;">
		<div class="form-group">
			<?php echo $form->labelEx($model,'id_team2'); ?>
			<?php ?>
					
					<?php echo $form->hiddenField($model,'id_team2',array('data-column'=>'id_team2')); ?>
					<button class="btn btn-default btn-block" id="id_team2_select" type="button" data-column="id_team2"><?php
						if(empty($model->team2)) echo "Выберите значение";
						else echo $model->team2->name; 
					?></button>
					
					<?php echo ""; ?>
			<?php echo $form->error($model,'id_team2'); ?>
		</div>
		<div class="form-group">
			<?php echo $form->labelEx($model,'score_team2_total'); ?>
			<?php echo $form->textField($model,'score_team2_total',array('class'=>'form-control','data-column'=>'score_team2_total')); ?>
			<?php echo $form->error($model,'score_team2_total'); ?>
		</div>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'ready'); ?>
		<?php echo $form->checkBox($model,'ready'); ?>
		<?php echo $form->error($model,'ready'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'text'); ?>
		<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>50, 'class'=>'editor-mini')); ?>
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