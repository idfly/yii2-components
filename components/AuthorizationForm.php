<?php

namespace idfly\components;
/**
 * User's authorization form; Usage:
 *
 * $authorizationForm = new \idfly\components\AuthorizationForm();
 * $authorizationForm->load(\yii::$app->request->post());
 * $success = $authorizationForm->login();
 *
 * Authorization passes through `login` field and model `\app\models\User`,
 * Custom parameters could be indicated in the constructor:

 * $authorizationForm = new \idfly\components\AuthorizationForm([
 *    'loginField' => 'email',
 *    'modelClass' => 'app\models\Admin',
 * ]);
 */
class AuthorizationForm extends \yii\base\Model
{

    /**
     * The field in the model which will be used for `login`.
     * @var string
     */
    public $loginField = 'login';

    /**
     * Model’s class, which will be used for `login`. The model should
     * require `Authorization` trait or implement the trait’s interface.
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
            $this->loginField => 'Логин',
            'password' => 'Пароль',
        ];
    }

    /**
     * Authorize.
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