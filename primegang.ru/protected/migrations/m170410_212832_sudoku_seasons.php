<?php

class m170410_212832_sudoku_seasons extends CDbMigration
{
	const s = 'seasons';
	const ss = 'sudoku_seasons';

	public function up()
	{
		$tableOptions = ($this->dbConnection->driverName === 'mysql') ? 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB' : null;

		$this->createTable(self::ss, array(
			'id' => 'pk',
			'name' => 'string',
			'alias' => 'string',
			'archive' => 'boolean',
		), $tableOptions);

		//клонируем таблицу сезонов
		$data = Yii::app()->db->createCommand()
			->from(self::s)
			->queryAll();

		foreach($data as $row)
			$this->insert(self::ss, $row);
	}

	public function down()
	{
		echo "m170410_212832_sudoku_seasons does not support migration down.\n";
		return false;
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