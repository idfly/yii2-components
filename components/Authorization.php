<?php

namespace idfly\components;

class AuthorizationRequired extends \yii\web\UnauthorizedHttpException {}

/**
 * Хелпер, позволяющий реализовывать авторизацию пользователей
 *
 * Пример подключения:
 *   class User extends \yii\db\ActiveRecord {
 *       use \idfly\components\Authorization;
 *   }
 *
 * В модели должны быть поля id, password для подключения данного класса.
 *
 * Если в классе объявлены beforeSave или afterFind, то нужно вызвать
 * _setPasswordHash внутри beforeSave и _hidePassword внутри afterFind
 * следующим образом:
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

    /**
     * Установить текущего пользователя в сессиюю. Установит значение CLASS.id,
     * CLASS.password в сессиию. Если передан null, тогда отменит авторизацию.
     * У модели должны быть свойства id и _password
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
     * Запросить идентификатор текущего авторизированного пользователя. Если
     * пользователь неавторизирован, вернёт null.
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
     * Запросить текущего авторизированного пользователя (инстанцию модели).
     * Если пользователь неавторизирован, вернёт null.
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
     * Проверить пароль пользователя
     * @param  string $password пароль в открытом виде
     * @return boolean
     */
    public function checkPassword($password)
    {
        return \yii::$app->security->validatePassword($password,
            $this->_password);
    }

    /**
     * beforeSave-хелпер; вызывает _setPasswordHash
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
     * Конвертировать пароль в открытом виде в хэш. Нужно обязательно вызвать
     * этот метод в beforeSave, если beforeSave переопределён в подклассе.
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
     * afterFind-хелпер; вызывает _hidePassword
     * @return [type] [description]
     */
    public function afterFind()
    {
        $this->_hidePassword();
        return parent::afterFind();
    }

    /**
     * Скрыть пароль из паблика. Нужно обязательно вызвать этот метод в
     * afterFind, если afterFind переопределён в подклассе.
     */
    protected function _hidePassword()
    {
        $this->_password = $this->password;
        $this->password = null;
    }

}
