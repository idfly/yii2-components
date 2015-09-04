<?php

namespace idfly\components;

class AuthorizationRequired extends \yii\web\UnauthorizedHttpException {}

/**
 * Helper, allowing users to implement user’s authorization; Usage:
 *
 *   class User extends \yii\db\ActiveRecord {
 *       use \idfly\components\Authorization;
 *   }
 *
 * There should be fields `id` and `password` in the model to use this class.
 * If in the class `beforeSave` or `afterFind` is declared, you need to call
 * `_setPasswordHash` inside `beforeSave` and `_hidePassword` inside
 * `afterFind` as follows:
 *
 *   class User extends \yii\db\ActiveRecord {
 *       use \app\components\Authorization;
 *
 *       public function beforeSave($insert) {
 *           if(!parent::beforeSave($insert)) {
 *               return false;
 *           }
 *
 *           return $this->_setPasswordHash($insert);
 *       }
 *
 *       public function afterFind() {
 *            $this->_hidePassword();
 *            return parent::afterFind();
 *       }
 *   }
 *
 * Class usage:
 *
 * User::getCurrent() - returns the model of authorized user or null
 * User::getCurrentId() - returns the `id` of authorized user or null
 * User::requireCurrent() - returns the user model or throw
 * \yii\web\UnauthorizedHttpException
 *
 * User::setCurrent($user) - set the current user
 *
 * $user->checkPassword(\yii::$app->request->post('password')) -
 * check user’s password
 */
trait Authorization
{

    public $_password;
    private static $_current;

    /**
     * Set current user into session. This will set the value `CLASS.id` and
     * `CLASS.password` into session. If `null` passed, then the authorization
     * would canceled.
     * The model should have the `id` and `_password` attributes.
     * @param \yii\base\Model $user
     */
    public static function setCurrent($user)
    {
        if(empty($user)) {
            \yii::$app->session[get_class() . '.id'] = null;
            \yii::$app->session[get_class() . '.password'] = null;
        } else {
            \yii::$app->session[get_class() . '.id'] = $user->id;
            \yii::$app->session[get_class() . '.password'] = $user->_password;
        }
    }

    /**
     * Запросить текущего авторизированного пользователя. Если текущий
     * пользователь не авторизирован, то выбросит AuthorizationRequired
     * @throws \idfly\components\AuthorizationRequired
     * @return \yii\base\Model
     */
    public static function requireCurrent()
    {
        $current = self::getCurrent();
        if(empty($current)) {
            throw new AuthorizationRequired('Для выполнения этого действия ' .
                'требуется авторизация');
        }

        return $current;
    }

    /**
     * Call for the current authorized user `id`. If the user is unauthorized,
     * returns null
     * @return integer
     */
    public static function getCurrentId()
    {
        $user = self::getCurrent();
        if(empty($user)) {
            return null;
        }

        return $user->id;
    }

    /**
     * Call for the current authorized user (model’s instance).
     * If the user is unauthorized, returns null.
     * @return \yii\base\Model
     */
    public static function getCurrent()
    {
        if(empty(self::$current)) {
            $userId = \yii::$app->session[get_class() . '.id'];
            if(empty($userId)) {
                return null;
            }

            $user = self::find()->where(['id' => $userId])->one();

            if(empty($user)) {
                return null;
            }

            if($user->_password != \yii::$app->session[get_class() . '.password']) {
                return null;
            }

            self::$_current = $user;
        }

        return self::$_current;
    }

    /**
     * Check the user's password
     * @param  string $password password in plaintext
     * @return boolean
     */
    public function checkPassword($password)
    {
        return \yii::$app->security->validatePassword($password,
            $this->_password);
    }

    /**
     * `beforeSave`-helper; calls `_setPasswordHash`
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert)) {
            return false;
        }

        $this->_setPasswordHash($insert);
        return true;
    }

    /**
     * Convert the plaintext password into a hash. It is necessary to call this
     * method in `beforeSave`, if beforeSave redefined in a subclass.
     */
    protected function _setPasswordHash()
    {
        if(empty($this->password)) {
            $this->password = $this->_password;
        } elseif((!empty($this->password))) {
            $this->password = \yii::$app->security->
                generatePasswordHash($this->password, 10);
        }
    }

    /**
     * `afterFind`-helper; calls `_hidePassword`
     * @return [type] [description]
     */
    public function afterFind()
    {
        $this->_hidePassword();
        return parent::afterFind();
    }

    /**
     * Hide the password from public. It is necessary to call this method in
     * `afterFind`, if `afterFind` redefined in a subclass.
     */
    protected function _hidePassword()
    {
        $this->_password = $this->password;
        $this->password = null;
    }

}
