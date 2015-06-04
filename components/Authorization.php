<?php

namespace idfly\components;

class AuthorizationRequired extends \yii\web\UnauthorizedHttpException {}

/**
 * Хелпер, позволяющий реализовывать авторизацию пользователей
 *
 * Пример подключения:
 *   class User extends \yii\db\ActiveRecord {
 *       use \app\components\Authorization;
 *   }
 *
 * В модели должны быть поля id, password для подключения данного класса.
 *
 * Если в классе объявлены beforeSave или afterFind, то нужно вызвать
 * _setPasswordHash внутри beforeSave и _hidePassword afterFind следующим
 * образом:
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
 * Использование класса:
 *
 * User::getCurrent() - вернёт модель авторизированного пользоватля или null
 * User::getCurrentId() - вернёт id авторизированного пользоватля или null
 * User::requireCurrent() - вернёт модель пользоватля или выбросит
 * \yii\web\UnauthorizedHttpException
 *
 * User::setCurrent($user) - установит текущего пользователя
 *
 * $user->checkPassword(\yii::$app->request->post('password')) - проверить
 * пароль пользователя
 */
trait Authorization
{

    public $_password;
    private static $_current;

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

    public static function requireCurrent()
    {
        $current = self::getCurrent();
        if(empty($current)) {
            throw new AuthorizationRequired('Для выполнения этого действия ' .
                'требуется авторизация');
        }

        return $current;
    }

    public static function getCurrentId()
    {
        $user = self::getCurrent();
        if(empty($user)) {
            return null;
        }

        return $user->id;
    }

    public static function getCurrent()
    {
        if(empty(self::$current)) {
            $userId = \yii::$app->session[get_class() . '.id'];
            if(empty($userId)) {
                return null;
            }

            $user = self::find()->where(['id' => $userId])->one();

            if($user->_password != \yii::$app->session[get_class() . '.password']) {
                return null;
            }

            self::$_current = $user;
        }

        return self::$_current;
    }

    public function checkPassword($password)
    {
        return \yii::$app->security->validatePassword($password,
            $this->_password);
    }

    public function beforeSave($insert)
    {
        if(!parent::beforeSave($insert)) {
            return false;
        }

        return $this->_setPasswordHash($insert);
    }

    protected function _setPasswordHash($insert)
    {
        if(empty($this->password)) {
            $this->password = $this->_password;
        } elseif((!empty($this->password))) {
            $this->password = \yii::$app->security->
                generatePasswordHash($this->password, 10);
        }

        return true;
    }

    public function afterFind()
    {
        $this->_hidePassword();
        return parent::afterFind();
    }

    protected function _hidePassword()
    {
        $this->_password = $this->password;
        $this->password = null;
    }

}