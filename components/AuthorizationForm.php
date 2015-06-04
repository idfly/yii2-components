<?php

namespace idfly\components;
/**
 * Форма авторизации пользователя; использование:
 *
 * $authorizationForm = new \idfly\components\AuthorizationForm();
 * $authorizationForm->load(\yii::$app->request->post());
 * $success = $authorizationForm->login();
 *
 * Авторизация проходит по полю login и модели \app\models\User, для
 * кастомные параметры можно указать в конструкторе:
 *
 * $authorizationForm = new \idfly\components\AuthorizationForm([
 *    'loginField' => 'email',
 *    'modelClass' => 'app\models\Admin',
 * ]);
 */
class AuthorizationForm extends \yii\base\Model {

    public $password;
    public $loginField = 'login';
    public $modelClass = 'app\models\User';

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