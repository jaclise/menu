<?php

use yii\db\Schema;
use console\migrations\BaseMigration;

class m160127_043131_init extends BaseMigration
{
    public function safeUp()
    {
    	$tableOptions = null;
    	
    	if ($this->db->driverName === 'mysql')
    	{
    		$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
    	}
    	
    	$this->createTable('{{%menu}}', [
    			'id' => Schema::TYPE_PK,
    			'name' => Schema::TYPE_STRING . '(45) NOT NULL',
    			'description' => Schema::TYPE_STRING . '(145)',
    			'url' => Schema::TYPE_STRING . '(145)',
    			'orderNum' => Schema::TYPE_INTEGER . ' DEFAULT \'1\'',
    			'permissionName' => Schema::TYPE_STRING . '(145)',
    			'parentId' => Schema::TYPE_INTEGER . ' DEFAULT \'0\'',
    			'created_at' => Schema::TYPE_INTEGER . ' NOT NULL',
    			'updated_at' => Schema::TYPE_INTEGER . ' NOT NULL',
    			'created_by' => Schema::TYPE_INTEGER . ' NOT NULL',
    			'updated_by' => Schema::TYPE_INTEGER . ' NOT NULL',
    	], $tableOptions);
    }

    public function safteDown()
    {
        $this->dropTable('{{%menu}}');
    }
}
