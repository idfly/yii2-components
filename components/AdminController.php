<?php

namespace idfly\components;

use \idfly\components\QueryHelper;

class BaseController extends \idfly\components\Controller {

    public $layout = 'admin.php';

    protected $queryKeys = [];
    protected $titles = [];
    protected $js = [];
    protected $_filters;
    protected $_lastSavedElement;

    public function beforeAction($action) {
        $user = \app\models\User::requireCurrent();
        $user->group->requireAccess($_SERVER['REQUEST_URI']);
        $this->_appendFilters($action);
        return parent::beforeAction($action);
    }

    public function _appendFilters($action) {
        $this->_filters =
            \app\models\Filter::find()->
            where(['key' => $this->_getKey() . '.' .$action->id])->
            andWhere('`filter`.`id` IN (
                SELECT `filter_id`
                    FROM `filter_group`
                    WHERE `filter_group`.`group_id` = :group_id
            ) OR (
                SELECT COUNT(*)
                    FROM `filter_group`
                    WHERE `filter_group`.`filter_id` = `filter`.`id`
            ) = 0', ['group_id' => \app\models\User::getCurrent()->group_id])->
            all();
    }

    public function actionIndex() {
        $list = $this->_getList();

        $key = $this->_getKey();
        $path = $this->module->getViewPath() . '/' . $key . '/index.php';
        if(file_exists($path)) {
            $view = 'index';
        } else {
            $view = '@app/admin/views/base/index.php';
        }

        $this->registerJs('index');
        $view = $this->_resolveView('index');

        $data = [
            $key => $list,
            '_elements' => $list,
            '_key' => $key,
            '_keyOne' => $this->_getKey(false),
            '_query' => QueryHelper::calculate(\Yii::$app->request->get()),
            '_title' => $this->_getTitle('index'),
            '_list' => $this->_resolveView('index-list', true),
            '_listFooter' => $this->_resolveView('index-list-footer', true),
            '_search' => $this->_resolveView('index-search', true),
            '_filter' => $this->_resolveView('index-filters', true),
            '_filters' => $this->_filters,
        ];

        return $this->_render($view, $data);
    }

    public function _renderContent($content) {
        if(\Yii::$app->request->isAjax) {
            return $content;
        } else {
            return $this->renderContent($content);
        }
    }

    public function _render($view, $data) {
        if(\Yii::$app->request->isAjax) {
            return $this->renderPartial($view, $data);
        } else {
            return $this->render($view, $data);
        }
    }

    public function actionCard($id) {
        $element = $this->_getElement($id);
        $view = $this->_resolveView('card');

        $this->registerJs('card');
        $key = $this->_getKey();

        $data = [
            $this->_getKey(false) => $element,
            '_key' => $key,
            '_keyOne' => $this->_getKey(false),
            '_element' => $element,
            '_cardBody' => $this->_resolveView('card-body', true),
            '_title' => $this->_getTitle('card'),
        ];

        return $this->_render($view, $data);
    }

    public function actionCreate() {
        $modelClass = $this->modelClass;
        $element = new $modelClass;
        $element->load(\Yii::$app->request->get());
        $this->_save($element);
        $this->registerJs('create');
        $this->registerJs('form');
        $viewData = $this->_getCreateViewData($element);
        return $this->render($this->_resolveView('create'), $viewData);
    }

    protected function _getCreateViewData($element) {
        $viewData = [
            $this->_getKey(false) => $element,
            '_element' => $element,
            '_key' => $this->_getKey(),
            '_keyOne' => $this->_getKey(false),
            '_form' => $this->_resolveView('form', true),
            '_createForm' => $this->_resolveView('create-form', true),
            '_title' => $this->_getTitle('create'),
        ];

        return $viewData;
    }

    public function actionUpdate($id) {
        $element = $this->_getElement($id);
        $this->_save($element);

        $this->registerJs('update');
        $this->registerJs('form');
        $viewData = $this->_getUpdateViewData($element);
        return $this->render($this->_resolveView('update'), $viewData);
    }

    public function _getUpdateViewData($element) {
        $viewData = [
            $this->_getKey(false) => $element,
            '_element' => $element,
            '_key' => $this->_getKey(),
            '_keyOne' => $this->_getKey(false),
            '_form' => $this->_resolveView('form', true),
            '_updateForm' => $this->_resolveView('update-form', true),
            '_title' => $this->_getTitle('update'),
        ];

        return $viewData;
    }

    public function actionDelete($id) {
        $this->_getElement($id)->delete();
        $this->_redirectBack();
    }

    protected function _getList() {
        $query = $this->getQuery();

        $limit = \yii::$app->request->get('limit', 100);
        $list = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $limit,
                'pageSizeParam' => 'limit',
            ],
        ]);

        return $list;
    }

    public function fieldsInputs($form, $element) {
        return [];
    }

    protected function _getTitle($type) {
        if(!empty($this->titles[$type])) {
            return $this->titles[$type];
        }

        return $this->_getKey() . ': ' . $type;
    }

    public function getQuery($query = null, $get = null) {
        $modelClass = $this->modelClass;

        if(empty($query)) {
            $query = $modelClass::find();
        }

        if($get === null) {
            $get = QueryHelper::calculate(\Yii::$app->request->get());
        }

        return $this->_getQuery(
            $modelClass::tableName(),
            $query,
            $get,
            !empty($this->allowedSearchKeys) ? $this->allowedSearchKeys : [],
            $this->queryKeys
        );
    }

    protected function _getQuery($table, $query, $get, $searchKeys = [],
        $queryKeys = []) {

        foreach($get as $key => $value) {
            $ignore = (
                !mb_strstr($key, '?') ||
                empty($value) ||
                !in_array($key, $searchKeys)
            );

            if($ignore) {
                continue;
            }

            list($field, $operator) = explode('?', $key);
            if(empty($operator)) {
                $operator = '=';
            }

            $fieldName = "`" . $table . "`.`$field`";
            if($value === 'NULL') {
                $query = $query->andWhere($fieldName . ' IS NULL');
            } elseif($value === 'NOT_NULL') {
                $query = $query->andWhere($fieldName . ' IS NOT NULL');
            } else {
                if(is_array($value)) {
                    $escapedValue = [];
                    foreach($value as $part) {
                        $escapedValue[] = \Yii::$app->db->quoteValue($part);
                    }

                    $escapedValue = '(' . implode(', ', $escapedValue) . ')';
                } else {
                    $escapedValue = \Yii::$app->db->quoteValue($value);
                }

                $query = $query->andWhere($fieldName . ' ' . $operator . ' ' .
                    $escapedValue);
            }
        }


        if(!empty($get['query']) && !empty($queryKeys)) {
            $query->andWhere($this->_getLikeQuery($get['query']));
        }

        if(empty($query->orderBy)) {
            $query->orderBy('`' . $table . '`.`id` DESC');
        };

        return $query;
    }

    protected function _getLikeQuery($query) {
        $likeQuery = ['or'];
        $modelClass = $this->modelClass;
        foreach($this->queryKeys as $field) {
            $likeQuery[] = "`" . $modelClass::tableName() . "`.`$field` LIKE " .
                \Yii::$app->db->quoteValue($query . '%');
        }

        return $likeQuery;
    }

    protected function _getKey($many = true) {
        $key = preg_replace('{^.*?\\\\([^\\\\]+)Controller$}', '$1',
            self::className());

        $key = strtolower($key);

        if(!$many) {
            $key = preg_replace('{s$}', '', $key);
        }

        return $key;
    }

    protected function _save($element) {
        if(!\Yii::$app->request->isPost) {
            return ;
        }

        $element->load(\Yii::$app->request->post());
        if($element->save()) {
            $this->_lastSavedElement = $element;
            $this->_redirectBack();
        }
    }

    protected function _resolveView($view, $fullPath = false) {
        $path = $this->module->getViewPath() . '/' . $this->_getKey() .
            '/' . $view . '.php';
        if(file_exists($path)) {
            if($fullPath) {
                $view = $this->module->getViewPath() . '/' .$this->_getKey() .
                    '/' . $view . '.php';
            }
        } else {
            if($fullPath) {
                $view = $this->module->getViewPath() . '/base/' . $view .
                    '.php';
            } else {
                $view = '@app/admin/views/base/' . $view . '.php';
            }
        }

        if($fullPath && !file_exists($view)) {
            return null;
        }

        return $view;
    }

    protected function registerJs($type) {
        if(empty($this->js[$type])) {
            return ;
        }

        $this->view->registerJs($this->js[$type]);
    }

}