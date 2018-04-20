<?php

use yii\db\Migration;

/**
 * Handles the creation of table `window`.
 */
class m180302_162527_create_window_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('window', [
            'id' => $this->primaryKey(),
            'name' => $this->string(30)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('window');
    }
}
