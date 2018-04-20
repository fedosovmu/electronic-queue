<?php

use yii\db\Migration;

/**
 * Handles the creation of table `ticket`.
 * Has foreign keys to the tables:
 *
 * - `window_operator`
 */
class m180302_163901_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('ticket', [
            'id' => $this->primaryKey(),
            'creation_time' => $this->datetime()->notNull(),
            'transfer_time' => $this->datetime(),
            'completion_time' => $this->datetime(),
            'window_oper_id' => $this->integer(),
        ]);

        // creates index for column `window_oper_id`
        $this->createIndex(
            'idx-ticket-window_oper_id',
            'ticket',
            'window_oper_id'
        );

        // add foreign key for table `window_operator`
        $this->addForeignKey(
            'fk-ticket-window_oper_id',
            'ticket',
            'window_oper_id',
            'window_operator',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `window_operator`
        $this->dropForeignKey(
            'fk-ticket-window_oper_id',
            'ticket'
        );

        // drops index for column `window_oper_id`
        $this->dropIndex(
            'idx-ticket-window_oper_id',
            'ticket'
        );

        $this->dropTable('ticket');
    }
}
