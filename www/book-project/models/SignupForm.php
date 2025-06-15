<?php

namespace app\models;

use yii\base\Model;

class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    public function rules()
    {
        return [
            [['username', 'email', 'password'], 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Это имя занято.'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'Этот email уже используется.'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'email' => 'Email',
        ];
    }

    /**
     * Регистрирует нового пользователя
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = 10; // активный пользователь
        $user->created_at = time();
        $user->updated_at = time();

        return $user->save() ? $user : null;
    }
}
