<?php

namespace idfly\components;

class AuthorizationForm extends \yii\base\Model {

    public $password;

    public function rules() {
        return [
            [[$this->loginField, 'password'], 'required'],
        ];
    }

    public function attributeLabels() {
        return [
            $this->loginField => 'Логин',
            'password' => 'Пароль',
        ];
    }

    public function login() {
        if(!$this->validate()) {
            return false;
        }

        $loginField = $this->loginField;

        $modelClass = $this->modelClass;
        $user =
            $modelClass::find()->
            where([$loginField => $this->{$loginField}])->
            one();

        if(empty($user)) {
            $this->addError($loginField, 'Такого логина не существует');
            return false;
        }

        if(!$user->checkPassword($this->password)) {
            $this->addError('password', 'Пароль неправильный');
            return false;
        }

        $modelClass::setCurrent($user);

        return true;
    }

}