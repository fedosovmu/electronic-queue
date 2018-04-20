<?php

use yii\db\Migration;

/**
 * Handles the creation of table `window_operator`.
 * Has foreign keys to the tables:
 *
 * - `window`
 * - `user`
 */
class m180302_163807_create_window_operator_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('window_operator', [
            'id' => $this->primaryKey(),
            'window_id' => $this->integer()->notNull(),
            'operator_id' => $this->integer()->notNull(),
            'entry_time' => $this->datetime()->notNull(),
            'exit_time' => $this->datetime(),
        ]);

        // creates index for column `window_id`
        $this->createIndex(
            'idx-window_operator-window_id',
            'window_operator',
            'window_id'
        );

        // add foreign key for table `window`
        $this->addForeignKey(
            'fk-window_operator-window_id',
            'window_operator',
            'window_id',
            'window',
            'id',
            'CASCADE'
        );

        // creates index for column `operator_id`
        $this->createIndex(
            'idx-window_operator-operator_id',
            'window_operator',
            'operator_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-window_operator-operator_id',
            'window_operator',
            'operator_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `window`
        $this->dropForeignKey(
            'fk-window_operator-window_id',
            'window_operator'
        );

        // drops index for column `window_id`
        $this->dropIndex(
            'idx-window_operator-window_id',
            'window_operator'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-window_operator-operator_id',
            'window_operator'
        );

        // drops index for column `operator_id`
        $this->dropIndex(
            'idx-window_operator-operator_id',
            'window_operator'
        );

        $this->dropTable('window_operator');
    }
}
