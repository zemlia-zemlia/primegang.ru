<style>
	.btn-block {overflow: hidden;}
</style>

<script>
	var _index = 0;
	$(function() {
		$("button.removeteam").click(function() {
			if(!confirm("Удалить эти команды из играющих в туре?")) return;
			
			var _element = $(this);
			var _teamId = _element.find("input[type=hidden][name=teamId]").val();
			var _url = "/admin/sudokutours/deleteteam";
			var _data = {'deleteteam':{'id':_teamId}};
			
			$.ajax({
				'type':'POST',
				'url':_url,
				'data':_data,
				'success':function(data) {
					_parent = _element.parents('div.item');
					_parent.remove();
				}
			});
			return false;
		});
		
		$('button#addTeams').click(function(){
			var _clone = $("table.teams tr.clone").clone(true);
			_index++
			
			var _id_old_1 = 'SudokuTours__teams_0_id_sudoku_team1';
			var _nm_old_1 = 'SudokuTours[_teams][0][id_sudoku_team1]';
			var _id_old_2 = 'SudokuTours__teams_0_id_sudoku_team2';
			var _nm_old_2 = 'SudokuTours[_teams][0][id_sudoku_team2]';
			var _id_old_d = 'SudokuTours__teams_0_division';
			var _nm_old_d = 'SudokuTours[_teams][0][division]';
			
			var _id_new_1 = 'SudokuTours__teams_'+_index+'_id_sudoku_team1';
			var _nm_new_1 = 'SudokuTours[_teams]['+_index+'][id_sudoku_team1]';
			var _id_new_2 = 'SudokuTours__teams_'+_index+'_id_sudoku_team2';
			var _nm_new_2 = 'SudokuTours[_teams]['+_index+'][id_sudoku_team2]';
			var _id_new_d = 'SudokuTours__teams_'+_index+'_division';
			var _nm_new_d = 'SudokuTours[_teams]['+_index+'][division]';
			
			_clone.find("input[data-column="+_id_old_1+"]").attr('name',_nm_new_1);
			_clone.find("input[data-column="+_id_old_2+"]").attr('name',_nm_new_2);
			_clone.find("select[data-column="+_id_old_d+"]").attr('name',_nm_new_d);
			
			_clone.find("[data-column="+_id_old_1+"]").attr('id',_id_new_1);
			_clone.find("[data-column="+_id_old_2+"]").attr('id',_id_new_2);
			_clone.find("[data-column="+_id_old_d+"]").attr('id',_id_new_d);
			
			_clone.find("[data-column="+_id_old_1+"]").attr('data-column',_id_new_1);
			_clone.find("[data-column="+_id_old_2+"]").attr('data-column',_id_new_2);
			_clone.find("[data-column="+_id_old_d+"]").attr('data-column',_id_new_d);
			
			_clone.removeClass("clone");
			
			$("table.teams").append(_clone);
		});
	});
</script>
<!--команды, играющие в паре-->
<?php
	$teams = SudokuToursTeams::model()->findAll("id_tour=:id_tour",array("id_tour"=>$model->id));

	// выборка списка дивизионов для редактируемого или последнего сезона
	$res = Yii::app()->db->createCommand('select `divisions`, `division_names` from `sudoku_seasons` where '
	. (isset($model->season->id) ? '`id` = '.$model->season->id : '`archive` = 0') . ' limit 1')->queryAll();
	$divisions = $res[0]['divisions'];
	$division_names = explode(';', $res[0]['division_names']);
	$division_select = array();
	for ($d = 0; $d < $divisions; $d++)
		$division_select[$d + 1] = isset($division_names[$d]) && $division_names[$d] ? $division_names[$d] : 'Дивизион '.($d + 1);

	//сначала постим поля для вбитых уже пар, затем для новых
	if(!empty($teams)) {
		foreach($teams as $team):
?>
		<div class="item">
			<div class="pull-right">
				<button class="btn btn-danger removeteam">
					<input type="hidden" name="teamId" value="<?php echo $team->id;?>">
					<span class="glyphicon glyphicon-remove"></span>
				</button>
			</div>
			<?= $team->team1->name.' &mdash; '.$team->team2->name.' @ '.$division_select[$team->division];?>
		</div>
	<?php endforeach;
	}
?>
<table class="table teams">
	<tr>
		<th>Команда 1</th>
		<th>Команда 2</th>
		<th>Дивизион</th>
	</tr>
	<tr class="item clone">
		<?php $pair = new SudokuToursTeams; $i = 0; ?>
		<td>
			<input type="hidden" name="_teams_index" value="<?php echo $i; ?>">
			<?php echo $form->hiddenField($pair,'id_sudoku_team1',array(
				'data-column'=>'SudokuTours__teams_'.$i.'_id_sudoku_team1',
				'name'=>'SudokuTours[_teams]['.$i.'][id_sudoku_team1]',
			)); ?>
			<button class="btn btn-default btn-block select" id="id_team1_select" type="button"
				data-href="/admin/sudokuteams/select"
				data-column="SudokuTours__teams_0_id_sudoku_team1">Выберите значение</button>
			<?php echo $form->error($pair,		'id_sudoku_team1'); ?>
		</td>
		<td>
			<?php echo $form->hiddenField($pair,'id_sudoku_team2',array(
				'data-column'=>'SudokuTours__teams_'.$i.'_id_sudoku_team2',
				'name'=>'SudokuTours[_teams]['.$i.'][id_sudoku_team2]',
			)); ?>
			<button class="btn btn-default btn-block select" id="id_team2_select" type="button"
				data-href="/admin/sudokuteams/select"
				data-column="SudokuTours__teams_0_id_sudoku_team2">Выберите значение</button>
			<?php echo $form->error($pair,		'id_sudoku_team2'); ?>
		</td>
		<td>
			<?php
			echo CHtml::dropDownList('SudokuTours[_teams]['.$i.'][division]', $select,
				$division_select,
				array(
					'class'=>'btn btn-default btn-block select',
					'style'=>'height:32px',
					'data-column'=>'SudokuTours__teams_'.$i.'_division'
				));
			?>
			<?php echo $form->error($pair,		'division'); ?>
		</td>
	</tr>
</table>
<button class="btn btn-primary btn-block" id="addTeams" type="button">Добавить пару команд</button>
