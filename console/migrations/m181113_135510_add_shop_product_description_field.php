<?php

use yii\db\Migration;

/**
 * Class m181113_135510_add_shop_product_description_field
 */
class m181113_135510_add_shop_product_description_field extends Migration
{
    public function up()
    {
        $this->addColumn('{{%shop_products}}', 'description', $this->text()->after('name'));
    }

    public function down()
    {
        $this->dropColumn('{{%shop_products}}', 'description');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181113_135510_add_shop_product_description_field cannot be reverted.\n";

        return false;
    }
    */
}
