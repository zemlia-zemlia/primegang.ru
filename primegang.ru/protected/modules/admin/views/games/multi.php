<?php
/* @var $this GamesController */
/* @var $dataProvider CActiveDataProvider */

$this->menu=array(
	array('label'=>'Список', 'url'=>array('index')),
	array('label'=>'Расчёт ГранПри', 'url'=>'/ajax/tests', 'linkOptions' => array('target' => '_blank')),
);
Yii::import('application.extensions.CJuiDateTimePicker.CJuiDateTimePicker');
?>

<script>
	var initMultiSelector = function(list_selector,dataColumn,gameId) {
		$(list_selector).find(".option").click(function(){
			var _option = $(this);
			var _dataId = _option.attr("data-id");
			$("input[data-gameid="+gameId+"][data-column="+dataColumn+"]").attr("value",_dataId);
			$("button[data-gameid="+gameId+"][data-column="+dataColumn+"]").html(_option.html());
			$("#selectModal").modal('hide');
			return false;
		});
	}
	$(function() {
		$("button.id_team1_select").click(function() {
			var gameId = $(this).attr("data-gameid");
			
			//фильтр команд по лиге
			var _id_league = $('input[data-column=id_league]').val();
			
			$.ajax({
				'type':"GET",
				'url':"/admin/teams/select?Teams[id_league]="+_id_league,
				'success':function(data) {
					$("#selectModal div.modal-body").html(data);
					initMultiSelector("#selectModal div.modal-body", "id_team1", gameId);
					$("#selectModal").modal('show');
				}
			});
		});
		$("button.id_team2_select").click(function() {
			var gameId = $(this).attr("data-gameid");

			//фильтр команд по лиге
			var _id_league = $('input[data-column=id_league]').val();
			
			$.ajax({
				'type':"GET",
				'url':"/admin/teams/select?Teams[id_league]="+_id_league,
				'success':function(data) {
					$("#selectModal div.modal-body").html(data);
					initMultiSelector("#selectModal div.modal-body", "id_team2", gameId);
					$("#selectModal").modal('show');
				}
			});
		});
	});	

////////////////////////////////////////////////
	var checkList = function() {
		var _check = ["id_league", "id_season", "id_tour" ];
		var errors = false;
		for(i=0;i<_check.length;i++) {
			var _selector = _check[i];
			var _sl_val = $("input[type=hidden][data-column="+_selector+"]").val(); 
		
			if(_sl_val <= 0 || _sl_val == NaN || _sl_val == undefined || _sl_val == "") {
				errors = true;
				break;
			}
		}
		if(errors) alert("Заполните все поля фильтра!");
		return !errors;
	}
	var updateList = function() {
	    $.fn.yiiListView.update('_flistview', { 
	        data: $('form.search-form').serialize()
	    });
	}
	$(function(){
		$('form.search-form').submit(function() {
			if(checkList())
				updateList();
		    return false;
		});
	});
////////////////////////////////////////////////
	$(function() {
		$("button#button_addRow").click(function() {
			if(!checkList()) return;
			
			var _index = $("table tr.add").length;
			
			var _template = $("table tr.add.clone");
			var _clone = _template.clone(true);

			_clone.insertBefore(_template);
			_clone.removeClass('clone');
			
			//заполним id_league, id_tour, id_season
			_id_league	 = $("input[data-column=id_league]").val();
			_id_season	 = $("input[data-column=id_season]").val();
			_id_tour	 = $("input[data-column=id_tour]")	.val();
			
			_clone.find('input#Add0_id_league')	.attr('value',_id_league);
			_clone.find('input#Add0_id_season')	.attr('value',_id_season);
			_clone.find('input#Add0_id_tour')	.attr('value',_id_tour);
			
			_clone.find('input#Add0_date')		.attr('value',"");
			
			//переделываем индексы на инпутах с Add[0] на Add[1] и т.д.
			_clone.find("input").each(function(index,element) {
				_name		 = $(element).attr('name');
				_id			 = $(element).attr('id');
				_data_gameid = $(element).attr('data-gameid');
				
				if(_name != "" && _name != undefined) {
					_rep = _name.replace("Add[0]","Add["+_index+"]");
					$(element).attr('name',_rep);
				}
				if(_id != "" && _id != undefined) {
					_rep = _id.replace("Add0","Add"+_index);
					$(element).attr('id',_rep);
				}
				if(_data_gameid != "" && _data_gameid != undefined) {
					_rep = _data_gameid.replace("add0","add"+_index);
					$(element).attr('data-gameid',_rep);
				}
			});
			
			//Add0_date - вешаем датепикер на поле даты
			_clone.find('input#Add'+_index+'_date').datetimepicker($.extend({showMonthAfterYear:false}, $.datepicker.regional['ru'], []));


			_clone.find("button").each(function(index,element) {
				_data_gameid = $(element).attr('data-gameid');
				if(_data_gameid != "" && _data_gameid != undefined) {
					_rep = _data_gameid.replace("add0","add"+_index);
					$(element).attr('data-gameid',_rep);
				}
			});
		});
	});
</script>


<div class="btn-toolbar" role="toolbar" aria-label="Games toolbar">
	<?php $this->renderPartial('_multisearch',array(
	    'model'=>$model,
	)); ?>
	<div class="btn-group" role="group" aria-label="tools">
		<button class="btn btn-primary" type="button" class="button_addRow" id="button_addRow">Добавить</button>
	</div>
</div>


<h1>Игры</h1>


<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>'', //always current page
	'method'=>'POST',
)); ?>

<table class="table">
	<tr style="font-weight: bold;">
		<td>Дата</td>
		<td>Команда дома</td>
		<td>Счет дома</td>
		<td>Команда в гостях</td>
		<td>Счет в гостях</td>
		<td>Состоялась</td>
	</tr>
	<?php $this -> widget( 'zii.widgets.CListView', array (
	        'dataProvider' =>$dataProvider ,
	        'itemView' =>'_multiview' ,
	        'id'=>'_flistview',
	        'template' =>"{items} \n <nav>{pager}</nav>" ,
	        'pagerCssClass' => 'cpager',
	        'pager' => array (
	       'firstPageLabel' =>'&laquo;' ,
	       'prevPageLabel' =>'&lt;' ,
	       'nextPageLabel' =>'&gt;' ,
	       'lastPageLabel' =>'&raquo;' ,
	       'selectedPageCssClass' => 'active',
	       'maxButtonCount' =>'5' ,
	       'header' =>'' ,
	       'htmlOptions' =>array ( 'class'=> 'pagination' )
	   ),                
	)); ?>
	<tr class="add clone">
		<input type="hidden" name="Add[0][id_league]"	 id="Add0_id_league" 	value="0"/>
		<input type="hidden" name="Add[0][id_season]"	 id="Add0_id_season" 	value="0"/>
		<input type="hidden" name="Add[0][id_tour]" 	 id="Add0_id_tour"		value="0"/>
		<td>
			<input type="text" name="Add[0][date]" id="Add0_date" value="0" data-column="date" class="form-control">
		</td>
		<td>
			<input type="hidden" name="Add[0][id_team1]" value="0" data-column="id_team1" data-gameid="add0">
			<button class="btn btn-default btn-block id_team1_select" id="id_team1_select" type="button" data-column="id_team1" data-gameid="add0">Выберите значение</button>
		</td>
		<td>
			<input type="text" name="Add[0][score_team1_total]" value="0" data-column="score_team1_total" class="form-control">
		</td>
		<td>
			<input type="hidden" name="Add[0][id_team2]" value="0" data-column="id_team2" data-gameid="add0">
			<button class="btn btn-default btn-block id_team2_select" id="id_team2_select" type="button" data-column="id_team2" data-gameid="add0">Выберите значение</button>
		</td>
		<td>
			<input type="text" name="Add[0][score_team2_total]" value="0" data-column="score_team2_total" class="form-control">
		</td>
		<td>
			<?php echo CHtml::checkBox("Add[0][ready]",0); ?>
		</td>
	</tr>
</table>
<div class="buttons">
	<button class="btn btn-block btn-primary" type="submit">Сохранить</button>
</div>
<?php $this->endWidget(); ?>

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