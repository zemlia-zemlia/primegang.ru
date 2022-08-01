<?php

class m170410_202243_prognosis_database_fix extends CDbMigration
{
	public function up()
	{
		/*
		$sql = "
			SELECT sp.id
			FROM `sudoku_prognosis` sp
			left join `sudoku_games` sg on sp.id_game = sg.id
			WHERE 1
			and sg.id_tour = 16
			and sp.id_player in (
				SELECT id FROM `sudoku_team_players` WHERE id_team in (13, 46)
			)		
		";
		$results = Yii::app()->db->createCommand()->setText($sql)->queryAll();
		$prognosis_ids = [];
		foreach($results as $res) $prognosis_ids[] = $res['id'];

		$command = Yii::app()->db->createCommand();
		$command->delete('sudoku_prognosis', array('in', 'id', $prognosis_ids) );
		*/
	}

	public function down()
	{
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}