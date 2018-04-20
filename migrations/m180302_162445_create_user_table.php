<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user`.
 */
class m180302_162445_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'login' => $this->string(30)->notNull(),
            'password' => $this->string(30)->notNull(),
            'role' => $this->string()->notNull(30),
            'full_name' => $this->string()->notNull(30),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('user');
    }
}
