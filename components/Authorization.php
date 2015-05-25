<?php

namespace idfly\components;

class AuthorizationRequired extends \yii\web\UnauthorizedHttpException {}

trait Authorization {

    public $_password;
    static private $_current;

    static public function setCurrent($user) {
        if(empty($user)) {
            \yii::$app->session[get_class() . '.id'] = null;
            \yii::$app->session[get_class() . '.password'] = null;
        } else {
            \yii::$app->session[get_class() . '.id'] = $user->id;
            \yii::$app->session[get_class() . '.password'] = $user->_password;
        }
    }

    static public function requireCurrentId() {
        return self::requireCurrent()->id;
    }

    static public function requireCurrent() {
        $current = self::getCurrent();
        if(empty($current)) {
            throw new AuthorizationRequired('Для выполнения этого действия ' .
                'требуется авторизация');
        }

        return $current;
    }

    static public function getCurrentId() {
        $user = self::getCurrent();
        if(empty($user)) {
            return null;
        }

        return $user->id;
    }

    static public function getCurrent() {
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

    public function checkPassword($password) {
        return \yii::$app->security->validatePassword($password,
            $this->_password);
    }

    public function beforeSave($insert) {
        return $this->_beforeSave($insert);
    }

    protected function _beforeSave($insert) {
        if(!parent::beforeSave($insert)) {
            return false;
        }

        if(empty($this->password)) {
            $this->password = $this->_password;
        } elseif((!empty($this->password))) {
            $this->password = \yii::$app->security->
                generatePasswordHash($this->password, 10);
        }

        return true;
    }

    public function afterFind() {
        return $this->_afterFind();
    }

    protected function _afterFind() {
        $this->_password = $this->password;
        $this->password = null;

        return parent::afterFind();
    }

}