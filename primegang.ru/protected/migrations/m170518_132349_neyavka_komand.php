<?php

class m170518_132349_neyavka_komand extends CDbMigration
{
	const stt = "sudoku_tours_teams";
	public function up()
	{
		$this->addColumn(self::stt, 'missing_team1', 'boolean');
		$this->addColumn(self::stt, 'missing_team2', 'boolean');
	}

	public function down()
	{
		$this->dropColumn(self::stt, 'missing_team1');
		$this->dropColumn(self::stt, 'missing_team2');
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