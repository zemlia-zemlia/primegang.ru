<?php
/* @var $this GamesController */
/* @var $data Games */
?>

<tr>
	<input type="hidden" name="Update[<?php echo $data->id; ?>][id_league]" value="<?php echo $data->id_league;?>"/>
	<input type="hidden" name="Update[<?php echo $data->id; ?>][id_season]" value="<?php echo $data->id_season;?>"/>
	<input type="hidden" name="Update[<?php echo $data->id; ?>][id_tour]" value="<?php echo $data->id_tour;?>"/>
	<td>
		<?php
		    $this->widget('CJuiDateTimePicker',array(
		    	'name'=>"Update[".$data->id."][date]",
		    	'id'=>"Update".$data->id."_date",
		        'model'=>$data, //Model object
		        'attribute'=>'date', //attribute name
		        'mode'=>'datetime', //use "time","date" or "datetime" (default)
		        'htmlOptions'=>array('class'=>'form-control'),
		        'language'=>'ru',
		        'options'=>array() // jquery plugin options
		    ));
		?>					
	</td>
	<td>
		<input type="hidden" name="Update[<?php echo $data->id; ?>][id_team1]" value="<?php echo $data->id_team1?>" data-column="id_team1" data-gameid="<?php echo $data->id;?>">
		<button class="btn btn-default btn-block id_team1_select" id="id_team1_select" type="button" data-column="id_team1" data-gameid="<?php echo $data->id;?>"><?php
			if(empty($data->team1)) echo "Выберите значение";
			else echo $data->team1->name; 
		?></button>
	</td>
	<td>
		<input type="text" name="Update[<?php echo $data->id; ?>][score_team1_total]" value="<?php echo $data->score_team1_total?>" data-column="score_team1_total" class="form-control">
	</td>
	<td>
		<input type="hidden" name="Update[<?php echo $data->id; ?>][id_team2]" value="<?php echo $data->id_team2?>" data-column="id_team2" data-gameid="<?php echo $data->id;?>">
		<button class="btn btn-default btn-block id_team2_select" id="id_team2_select" type="button" data-column="id_team2" data-gameid="<?php echo $data->id;?>"><?php
			if(empty($data->team2)) echo "Выберите значение";
			else echo $data->team2->name; 
		?></button>
	</td>
	<td>
		<input type="text" name="Update[<?php echo $data->id; ?>][score_team2_total]" value="<?php echo $data->score_team2_total?>" data-column="score_team2_total" class="form-control">
	</td>
	<td>
		<?php echo CHtml::checkBox("Update[".$data->id."][ready]",$data->ready); ?>
	</td>
</tr>