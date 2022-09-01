<?php

class m220825_083134_create_add_score_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('add_score', [

            'id'           => 'pk',
            'id_sudoku_team'       => 'integer',
            'id_season'       => 'integer',
            'goals'       => 'integer',
            'missing'       => 'integer',

        ]);
	}

	public function down()
	{
        $this->delete('add_score');

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