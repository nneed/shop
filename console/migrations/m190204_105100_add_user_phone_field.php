<?php

use yii\db\Migration;

/**
 * Class m190204_105100_add_user_phone_field
 */
class m190204_105100_add_user_phone_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'phone', $this->string()->notNull());

        $this->createIndex('{{%idx-users-phone}}', '{{%users}}', 'phone', true);
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'phone');
    }
}
