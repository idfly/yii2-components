<?php

namespace idfly\components;

/**
 * Базовый контроллер; добавляет два protected-метода в контроллеры:
 *
 *   - _redirectBack($default = null)
 *   - _getElement($id, $class = null)
 *
 * \yii\web\HttpException(404)
 */
class Controller extends \yii\web\Controller
{
    /**
     * Класс модели, с которым работет контроллер
     * @var [type]
     */
    protected $modelClass;

    /**
     * Вернуть пользователя назад, если параметр _redirect установлен в
     * get-параметрах;
     *
     * Если параметра _redirect нет, тогда будет использован роут $default из
     * аргументов
     *
     * Если $default равен null, тогда будет исполльзован путь
     * /[module]/[controller]/[index]
     *
     * @param  string $default путь по умолчанию для перенаправления пользователя
     * @return yii\web\Response description
     */
    protected function _redirectBack($default = null)
    {
        $url = \Yii::$app->request->get('_redirect');
        if(empty($url)) {
            if($default !== null) {
                $url = $default;
            } else {
                $url = \Yii::$app->urlManager->createUrl([$this->module->id .
                    '/' . $this->id . '/' . $this->defaultAction]);
            }
        }

        return $this->redirect($url);
    }

    /**
     * Найти элемент с заданным id и заданным классом; если элемент не найден,
     * то будет выброшено исключение; если класс не задан, то будет использован
     * класс по умолчанию ($modelClass). Если оба класса не заданы, то будет
     * выброшено исключение.
     *
     * @param  integer|array $id идентификатор или условие where по которому
     *         выполнять поиск в виде ['key' => 'value']
     * @param  string $class класс
     * @throws \Exception
     * @throws \yii\web\HttpException
     * @return \yii\base\Model
     */
    protected function _getElement($id, $class = null)
    {
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

        if(!is_array($id)) {
            $id = ['id' => $id];
        }

        $element =
            $class::find()->
            where($id)->
            one();

        if(empty($element)) {
            throw new \yii\web\HttpException(404);
        }

        return $element;
    }

    /**
     * Заполнить мета-теги страницы
     *
     * @param \yii\base\View $view объект представления
     * @param array $metaArray массив с данными
     */
    public static function registerMetaTags($view, $metaArray)
    {
        foreach($metaArray as $name => $content) {
            $view->registerMetaTag([
                'name' => $name,
                'content' => $content,
            ]);
        }
    }

}
