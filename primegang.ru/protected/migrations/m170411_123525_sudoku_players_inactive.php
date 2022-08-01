<?php

class m170411_123525_sudoku_players_inactive extends CDbMigration
{
	public function up()
	{
		$this->update('sudoku_team_players', array('active' => 1));
	}

	public function down()
	{
		$this->update('sudoku_team_players', array('active' => 0));
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