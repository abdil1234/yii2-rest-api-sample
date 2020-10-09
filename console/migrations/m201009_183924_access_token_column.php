<?php

use yii\db\Migration;

/**
 * Class m201009_183924_access_token_column
 */
class m201009_183924_access_token_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'access_token', $this->string()->defaultValue(null));

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m201009_183924_access_token_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m201009_183924_access_token_column cannot be reverted.\n";

        return false;
    }
    */
}
