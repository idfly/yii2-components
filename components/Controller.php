<?php

namespace idfly\components;

/**
 *  Basic controller; adds two protected-methods into controllers:
 *
 *   - _redirectBack($default = null)
 *   - _getElement($id, $class = null)
 *
 * \yii\web\HttpException(404)
 */
class Controller extends \yii\web\Controller
{
    /**
     * Model’s class, which is operated by controllers.
     * @var [type]
     */
    protected $modelClass;

    /**
     * Return the user back, if the _redirect parameter is set in
     * Get-parameters;
     *
     * If there is no _redirect parameter, then the $default route from
     * the arguments will be used.
     *
     * If $ default is null, then the path will be used
     * /[module]/[controller]/[index]
     *
     * @param  string $default default path for forwarding user
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
     * Find the element with the given `id`, and `class`; if the element is
     * not found, the exception will be thrown; If a class is not indicated,
     * there will be used the default class ($modelClass). If the both
     * classes are not set, the exception will be thrown.
     *
     * @param  integer|array $id id or case `where` by which the search should
     * be done as ['key' => 'value']
     * @param  string $class class
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
     * Fill in the meta-tags of the page
     *
     * @param \yii\base\View $view view object
     * @param array $metaArray array with data
     */
    public static function registerMetaTags($view, $metaArray)
    {
        foreach($metaArray as $name => $content) {
            if(empty($content)) {
                continue;
            }

            $view->registerMetaTag([
                'name' => $name,
                'content' => $content,
            ]);
        }
    }

}
