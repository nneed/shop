<?php

use yii\db\Migration;

/**
 * Class m181205_133751_add_shop_product_status_field
 */
class m181205_133751_add_shop_product_status_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_products}}', 'status', $this->smallInteger()->notNull());
        $this->update('{{%shop_products}}', ['status' => 1]);
    }

    public function down()
    {
        $this->dropColumn('{{%shop_products}}', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181205_133751_add_shop_product_status_field cannot be reverted.\n";

        return false;
    }
    */
}
