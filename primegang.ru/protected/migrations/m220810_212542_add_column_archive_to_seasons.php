<?php

class m220810_212542_add_column_archive_to_seasons extends CDbMigration
{
	public function up()
	{
	    $this->addColumn('seasons', 'archive', 'boolean');
	}

	public function down()
	{
        $this->dropColumn('seasons', 'archive');

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