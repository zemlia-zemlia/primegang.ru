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
				
				//скидываем значение тура
				$("input[data-column=id_tour]").attr("value",0);
				$("button[data-column=id_tour]").html("Тур");
				
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

				//скидываем значение тура
				$("input[data-column=id_tour]").attr("value",0);
				$("button[data-column=id_tour]").html("Тур");
				
				$("#selectModal").modal('show');
			}
		});
	});
	$("button#id_tour_select").click(function(){
		//фильтруем по лиге и сезону
		var _id_season = $('input[data-column=id_season]').val();
		var _id_league = $('input[data-column=id_league]').val();
		
		$.ajax({
			'type':"GET",
			'url':"/admin/tours/select?Tours[id_season]="+_id_season+"&Tours[id_league]="+_id_league,
			'success':function(data) {
				$("#selectModal div.modal-body").html(data);
				initSelector("#selectModal div.modal-body", "id_tour");
				
				$("#selectModal").modal('show');
			}
		});
	});
});
</script>	



<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'htmlOptions'=>array('class'=>"search-form"),
)); ?>
	<?php echo $form->hiddenField($model,'id_league',array('data-column'=>'id_league')); ?>
	<?php echo $form->hiddenField($model,'id_season',array('data-column'=>'id_season')); ?>
	<?php echo $form->hiddenField($model,'id_tour',array('data-column'=>'id_tour')); ?>

	<div class="btn-group" role="group" aria-label="search">
		<button class="btn btn-default" id="id_league_select" type="button" data-column="id_league"><?php
			if(empty($model->league)) echo "Лига";
			else echo $model->league->name; 
		?></button>
		<button class="btn btn-default" id="id_season_select" type="button" data-column="id_season"><?php
			if(empty($model->season)) echo "Сезон";
			else echo $model->season->name; 
		?></button>
		<button class="btn btn-default" id="id_tour_select" type="button" data-column="id_tour"><?php
			if(empty($model->tour)) echo "Тур";
			else echo $model->tour->name; 
		?></button>
		<button class="btn btn-primary" type="submit">Фильтровать</button>
	</div>

<?php $this->endWidget(); ?>
