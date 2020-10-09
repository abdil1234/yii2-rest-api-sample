<?php

use yii\db\Migration;

/**
 * Class m191205_170938_user_data
 */
class m191205_170938_user_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('user', [
            'username',
            'auth_key',
            'password_hash',
            'password_reset_token',
            'email',
            'status',
            'created_at',
            'updated_at',
        ], [
            ['admin','23123131', '$2y$12$Q0qaeN71TZ1Sm8qXDRI9AOevpM5xQuhDVoaTegEU3sJgDMn.v3d8u', '31231', 'admin@gmail.com', 10, 21312,123131],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191205_170938_user_data cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m191205_170938_user_data cannot be reverted.\n";

        return false;
    }
    */
}
