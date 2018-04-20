<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "window".
 *
 * @property int $id
 * @property string $name
 *
 * @property WindowOperator[] $windowOperators
 */
class Window extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'window';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWindowOperators()
    {
        return $this->hasMany(WindowOperator::className(), ['window_id' => 'id']);
    }

    public static function createWindow($window_name)
    {
        $window = new Window();
        $window->name = $window_name;
        $window->save();
        return $window;
    }

    public static function freeWindows()
    {
        $busy_windows_id = [];
        $busy_window_operators =  WindowOperator::find()->where(['exit_time' => null])->all();
        for($i = 0; $i < count($busy_window_operators); $i++) {
            $busy_windows_id[] = $busy_window_operators[$i]['window_id'];
        }
        $windows = Window::find()->all();
        $free_windows = [];
        for($i = 0; $i < count($windows); $i++) {
            if(!in_array($windows[$i]['id'], $busy_windows_id)){
                $free_windows[] = $windows[$i];
            }
        }
        return $free_windows;
    }

    public function deleteWindow()
    {
        $this->delete();
    }
}
