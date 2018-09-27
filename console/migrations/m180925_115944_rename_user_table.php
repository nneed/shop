<?php

use yii\db\Migration;

/**
 * Class m180925_115944_rename_user_table
 */
class m180925_115944_rename_user_table extends Migration
{
    public function up()
    {
        $this->renameTable('{{%user}}', '{{%users}}');
    }

    public function down()
    {
        $this->renameTable('{{%users}}', '{{%user}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180925_115944_rename_user_table cannot be reverted.\n";

        return false;
    }
    */
}
