<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password', 'role', 'full_name'], 'required'],
            [['login', 'password'], 'string', 'max' => 30],
            [['role', 'full_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'login' => 'Login',
            'password' => 'Password',
            'role' => 'Role',
            'full_name' => 'Full Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWindowOperators()
    {
        return $this->hasMany(WindowOperator::className(), ['operator_id' => 'id']);
    }

    public static function findByLogin($login)
    {
        return self::find()->where(['login' => $login])->one();
    }

    public function validatePassword($password)
    {
        return $password == $this->password;
    }
    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function getActiveWindowOperator()
    {
        return WindowOperator::findOne(["operator_id"=>$this->id, "exit_time"=>null]);
    }
}
