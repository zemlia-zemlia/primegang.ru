<?php
/* @var $this ToursController */
/* @var $model Tours */
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
	});
</script>	



<div class="wide form search-form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

			<div class="form-group">
			<?php echo $form->label($model,'id_league'); ?>
			<?php ?>
				
				<?php echo $form->hiddenField($model,'id_league',array('data-column'=>'id_league')); ?>
				<button class="btn btn-default btn-block" id="id_league_select" type="button" data-column="id_league"><?php
					if(empty($model->league)) echo "Выберите значение";
					else echo $model->league->name; 
				?></button>
				
				<?php echo ""; ?>
		</div>
				<div class="form-group">
			<?php echo $form->label($model,'id_season'); ?>
			<?php ?>
				
				<?php echo $form->hiddenField($model,'id_season',array('data-column'=>'id_season')); ?>
				<button class="btn btn-default btn-block" id="id_season_select" type="button" data-column="id_season"><?php
					if(empty($model->season)) echo "Выберите значение";
					else echo $model->season->name; 
				?></button>
				
				<?php echo ""; ?>
		</div>
				<div class="form-group">
			<?php echo $form->label($model,'tour_number'); ?>
			<?php echo $form->textField($model,'tour_number',array('class'=>'form-control','data-column'=>'tour_number')); ?>
		</div>
	
	<div class="row buttons">
		<button class="btn btn-primary" type="submit">Искать</button>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->

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