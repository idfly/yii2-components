<?php

namespace idfly\components;

class Controller extends \yii\web\Controller {

    protected function _redirectBack() {
        $url = \Yii::$app->request->get('_redirect');
        if(empty($url)) {
            $url = \Yii::$app->urlManager->createUrl('admin/' . $this->id);
        }

        $this->redirect($url);
    }

    protected function _getElement($id, $class = null) {
        if(empty($class)) {
            $class = $this->modelClass;
        }

        if(empty($id)) {
            throw new \yii\web\HttpException(404);
        }

        $element = $class::find()->
            where(['id' => $id])->
            one();

        if(empty($element)) {
            throw new \yii\web\HttpException(404);
        }

        return $element;
    }

}