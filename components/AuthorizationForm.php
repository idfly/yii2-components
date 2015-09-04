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
class AuthorizationForm extends \yii\base\Model
{

    /**
     * Поле модели, которое будет использоваться для логина.
     * @var string
     */
    public $loginField = 'login';

    /**
     * Класс модели, который будет использоваться для логина. Модель должна
     * подключать трейт Authorization или имплементировать интерфейс этого
     * трейта.
     * @var string
     */
    public $modelClass = 'app\models\User';

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[$this->loginField, 'password'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            $this->loginField => 'Login',
            'password' => 'Password',
        ];
    }

    /**
     * Выполнить авторизацию.
     * @return boolean
     */
    public function login()
    {
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