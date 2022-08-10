<?php

class m220807_071718_create_addpoints_table extends CDbMigration
{
	public function up()
	{
	  $this->createTable('addpoints', [

          'id'           => 'pk',
          'id_sudoku_team'       => 'integer',
          'id_tour'       => 'integer',
          'points'       => 'integer',

      ]);
	}

	public function down()
	{
       $this->delete('addpoints');
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