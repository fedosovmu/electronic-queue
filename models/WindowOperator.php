<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "window_operator".
 *
 * @property int $id
 * @property int $window_id
 * @property int $operator_id
 * @property string $entry_time
 * @property string $exit_time
 *
 * @property Ticket[] $tickets
 * @property User $operator
 * @property Window $window
 */
class WindowOperator extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'window_operator';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['window_id', 'operator_id', 'entry_time'], 'required'],
            [['window_id', 'operator_id'], 'integer'],
            [['entry_time', 'exit_time'], 'safe'],
            [['operator_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['operator_id' => 'id']],
            [['window_id'], 'exist', 'skipOnError' => true, 'targetClass' => Window::className(), 'targetAttribute' => ['window_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'window_id' => 'Window ID',
            'operator_id' => 'Operator ID',
            'entry_time' => 'Entry Time',
            'exit_time' => 'Exit Time',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::className(), ['window_oper_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOperator()
    {
        return $this->hasOne(User::className(), ['id' => 'operator_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWindow()
    {
        return $this->hasOne(Window::className(), ['id' => 'window_id']);
    }

    public static function createWindowOperator($window_id, $operator_id)
    {
        $window_operator = new WindowOperator();
        $window_operator->window_id = $window_id;
        $window_operator->operator_id = $operator_id;
        $window_operator->entry_time = date('Y-m-d H:i:s');
        $window_operator->save();
        return $window_operator;
    }

    public function exitWindowOperator()
    {
        $this->exit_time = date('Y-m-d H:i:s');
        $this->save();
    }

    public static function workingWindowOperators()
    {
        return WindowOperator::find()->where(['exit_time'=>null])->all();
    }

    public static function freeWindowOperators()
    {
        $busy_tickets = Ticket::find()->where(['not', ['transfer_time' => null]])
            ->andWhere(['completion_time' => null])->all();
        $working_ops = WindowOperator::workingWindowOperators();
        $busy_windowops_id = [];
        for ($i = 0; $i < count($busy_tickets); $i++) {
            $busy_windowops_id[] = $busy_tickets[$i]['window_oper_id'];
        }
        $free_windowops = [];
        for ($i = 0; $i < count($working_ops); $i++) {
            if (!in_array($working_ops[$i]['id'], $busy_windowops_id)) {
                $free_windowops[] = $working_ops[$i];
            }
        }
        return $free_windowops;
    }

    public static function exitWindowByOperId($id)
    {
        $wo = WindowOperator::findOne(["operator_id"=>$id, "exit_time"=>null])
            ->exitWindowOperator();
    }
}
