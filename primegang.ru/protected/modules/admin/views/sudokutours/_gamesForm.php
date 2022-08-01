<!--матчи, на которые будут делаться прогнозы-->
<table class="table">
	<thead>
		<tr>
			<th>Тайм</th>
			<th>Дата</th>
			<th>Команда 1</th>
			<th>Счет 1</th>
			<th>Счет 2</th>
			<th>Команда 2</th>
			<th>Состоялась</th>
		</tr>
	</thead>
	<tbody>
		<?php
			$criteria = new CDbCriteria;
			$criteria->condition = "id_tour=:id_tour";
			$criteria->params = array("id_tour"=>$model->id);
			$criteria->order="time,id"; 
			$games = SudokuGames::model()->findAll($criteria);
			$i=0;
			//сначала постим поля для вбитых уже игр, затем для новых
			if(!empty($games)) {
				foreach($games as $game):
					$this->renderPartial("_gameForm", array('form'=>$form,'game'=>$game,'i'=>$i));
					$i++;
				endforeach;	
			}
			while($i < 6):
				//если игр вбито меньше 6, отображаем форму для остальных
				$game = new SudokuGames;
				$this->renderPartial("_gameForm", array('form'=>$form,'game'=>$game,'i'=>$i));
				$i++;
			endwhile;
		?>
	</tbody>
</table>
