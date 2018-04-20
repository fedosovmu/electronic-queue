<?php

use yii\db\Migration;

/**
 * Handles the creation of table `token`.
 */
class m180329_091345_create_token_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('token', [
            'id' => $this->primaryKey(),
            'token' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('token');
    }
}
