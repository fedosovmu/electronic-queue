<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property string $creation_time
 * @property string $transfer_time
 * @property string $completion_time
 * @property int $window_oper_id
 *
 * @property WindowOperator $windowOper
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['creation_time'], 'required'],
            [['creation_time', 'transfer_time', 'completion_time'], 'safe'],
            [['window_oper_id'], 'integer'],
            [['window_oper_id'], 'exist', 'skipOnError' => true, 'targetClass' => WindowOperator::className(), 'targetAttribute' => ['window_oper_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'creation_time' => 'Creation Time',
            'transfer_time' => 'Transfer Time',
            'completion_time' => 'Completion Time',
            'window_oper_id' => 'Window Oper ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWindowOper()
    {
        return $this->hasOne(WindowOperator::className(), ['id' => 'window_oper_id']);
    }

    public static function createTicket()
    {
        $ticket = new Ticket();
        $ticket->creation_time = date('Y-m-d H:i:s');
        $ticket->save();
        return $ticket;
    }

    public function transferTicket($window_oper_id)
    {
        $this->transfer_time = date('Y-m-d H:i:s');
        $this->window_oper_id = $window_oper_id;
        $this->save();
    }

    public function completeTicket()
    {
        $this->completion_time = date('Y-m-d H:i:s');
        $this->save();
    }

    public static function freeTickets()
    {
        return Ticket::find()->where(['window_oper_id'=>null])->all();
    }

    public static function workingTikets()
    {
//        return Ticket::find()->where(['not', ['window_oper_id'=>null]])
//            ->andWhere(['completion_time'=>null])->all();
        return Ticket::find()->where(['completion_time'=>null])->all();
    }

    public function getTicketWindowName()
    {
        if($this->window_oper_id != null){
            $winoper = WindowOperator::findOne($this->window_oper_id);
            return Window::findOne($winoper->window_id)['name'];
        }
    }

    public static function workingTicketsWindow()
    {
        $ticketwindow = [];
        $tickets = Ticket::workingTikets();
        foreach ($tickets as $ticket){
            $ticketwindow[] = ["id"=>$ticket->id, "window_oper_id"=>$ticket->window_oper_id, "window"=>$ticket->getTicketWindowName()];
        }
        return $ticketwindow;
    }
    public static function distributeTickets()
    {
        while (Ticket::freeTickets() and WindowOperator::freeWindowOperators()) {
            $rand_oper = random_int(0, count(WindowOperator::freeWindowOperators())-1);
            $rand_ticket = random_int(0, count(Ticket::freeTickets())-1);
            Ticket::freeTickets()[$rand_ticket]->transferTicket(WindowOperator::freeWindowOperators()[$rand_oper]->id);
        }
    }
    public static function completeTickerByOperId($id)
    {
        $wo = WindowOperator::findOne(["operator_id"=>$id, "exit_time"=>null]);
        $ticket = Ticket::findOne(["window_oper_id"=>$wo->id, "completion_time"=>null]);
        $ticket->completeTicket();
    }
}