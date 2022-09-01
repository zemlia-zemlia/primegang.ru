<?php

class m220901_081910_alter_column_season_addpoints_table extends CDbMigration
{
	public function up()
	{
	    $this->delete('addpoints', '1=1');
	    $this->renameColumn('addpoints', 'id_tour', 'id_season');
	}

	public function down()
	{
		echo "m220901_081910_alter_column_season_addpoints_table does not support migration down.\n";
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