<?php

namespace idfly\components;

/**
 * Базовый контроллер; добавляет два protected-метода в контроллеры:
 *
 * _redirectBack($default = null) - вернуть пользователя назад, если параметр
 * _redirect установлен в get-параметрах; если параметра _redirect нет, тогда
 * будет использован роут $default из аргументов; если $default равен null,
 * тогда будет исполльзован путь /[module]/[controller]/[index]
 *
 * _getElement($id, $class = null) - получить элемент с указанным идентификатором
 * указанного класса; если класс не указан, тогда для поиска будет использован
 * $this->modelClass; если $id пуст или  элемент не найден, тогда класс выбросит
 * \yii\web\HttpException(404)
 */
class Controller extends \yii\web\Controller {

    protected function _redirectBack($default = null) {
        $url = \Yii::$app->request->get('_redirect');
        if(empty($url)) {
            if($default !== null) {
                $url = $default;
            } else {
                $url = \Yii::$app->urlManager->createUrl([$this->module->id .
                    '/' . $this->id . '/' . $this->defaultAction]);
            }
        }

        $this->redirect($url);
    }

    protected function _getElement($id, $class = null) {
        if(empty($class)) {
            if(empty($this->modelClass)) {
                throw new \Exception('$class or $this->modelClass should ' .
                    'be set');
            }

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