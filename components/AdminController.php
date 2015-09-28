<?php

namespace idfly\components;

use yii\helpers\ArrayHelper;

/**
 * Admin panel Controller; adds basic operations (list, view, add, update,
 * delete) to the controllers, which are inherited from it.
 *
 * All templates resolves by following rules:
 *
 *  1. Needed template is searched in views/[controller] folder
 *  2. If it is not found, you may use admin/views/layouts/admin folder
 *      (overridden in the variable $viewPath)
 *  3. If it is not found, you may use vendor/idfly/yii2-components/views/admin
 *      folder
 *
 * The page title is taken from the array protected $titles.
 *
 * Javascript of the page is taken from array protected $js.
 *
 * The controller works with a model, indicated in the variable
 * protected $modelClass.
 *
 * Adds 5 actions to the controller:
 *   - actionIndex
 *   - actionView
 *   - actionCreate
 *   - actionUpdate
 *   - actionDelete
 *
 * To default view is displayed normally you need to include AdminAsset in
 * assets.
 *
 */
class AdminController extends \idfly\components\Controller
{

    /**
     * Ссылка на layout
     * @var string
     */
    public $layout = 'admin.php';

    /**
     * List of keys which gives the access to search on query through "AND";
     * the example of array: ['date?>', 'price?<=', 'name?'] (converts the
     * query: `date` more value, `price` less or equal, `name` is equal to
     * the value)
     *
     * If GET receives NULL value when the query will be look like IS NULL,
     * if it receives NOT NULL while "IS NOT NULL" for the empty operator
     * ('name?')
     *
     * @var array
     */
    protected $allowedQueryKeys = null;

    /**
     * The section titles; the possible values of the keys array:
     *   - index
     *   - view
     *   - create
     *   - update
     * @var array
     */
    protected $titles = [];

    /**
     * javascript, which is needed to execute for some section;
     * the possible values of the keys array:
     *   - index
     *   - view
     *   - create
     *   - form
     *   - update
     * @var array
     */
    protected $js = [];

    /**
     * last saved (added or updated) element
     * @var mixed
     */
    protected $_lastSavedElement;

    /**
     * path for custom views
     * @var string
     */
    protected $viewPath = 'admin/views/layouts/admin';

    /**
     * Display the list of elements.
     *
     * - displays the list of models
     *
     * - the list of models getting from the method  _getList
     *
     * - the search of models is automatically produces based on
     * GET-parameters and variables of queryKeys, searchKeys classes (see
     * phpdoc to $queryKeys and $searchKeys)
     *
     * - title "index" is used
     *
     * - javascript "index" is used
     *
     * - following views is used:
     *   - index.php - shell
     *   - index-list.php - the list of models
     *   - index-footer.php - the footer of the list (pagination)
     *
     * @return string html-code of the page
     */
    public function actionIndex()
    {
        $list = $this->_getList();

        $key = $this->_getKey();
        $this->_registerJs('index');
        $view = $this->_resolveView('index');

        $data = [
            $key => $list,
            '_elements' => $list,
            '_key' => $key,
            '_keyOne' => $this->_getKey(false),
            '_title' => $this->_getTitle('index'),
            '_list' => $this->_resolveView('index-list', true),
            '_footer' => $this->_resolveView('index-footer', true),
            '_search' => $this->_resolveView('index-search', true),
            '_filter' => $this->_resolveView('index-filters', true),
        ];

        return $this->_render($view, $data);
    }

    public function actionListJson()
    {
        \Yii::$app->response->format = 'json';
        $list = $this->_getQuery()->asArray()->all();
        return json_encode($list, JSON_UNESCAPED_UNICODE);
    }

    /**
     * page render; if ajax-request calls - render will be promoted without
     * layout
     * @param  string $view view-file
     * @param  array $data data for view
     * @return string html-code of the page
     */
    protected function _render($view, $data)
    {
        if(\Yii::$app->request->isAjax) {
            return $this->renderPartial($view, $data);
        } else {
            return $this->render($view, $data);
        }
    }

    /**
     * View element.
     *
     * - displays the page of model view
     *
     * - title "view" is used
     *
     * - javascript "view" is used
     *
     * - data for view gets through the method _getViewData
     *
     * - following views is used:
     *   - view.php - shell
     *   - view-body.php - contents
     *
     * @param  int $id element ID
     * @return string html-code of the page
     */
    public function actionView($id)
    {
        $element = $this->_getElement($id);
        $view = $this->_resolveView('view');
        $this->_registerJs('view');
        $data = $this->_getViewData($element);

        return $this->_render($view, $data);
    }

    protected function _getViewData($element)
    {
        $data = [
            $this->_getKey(false) => $element,
            '_key' => $this->_getKey(),
            '_keyOne' => $this->_getKey(false),
            '_element' => $element,
            '_viewBody' => $this->_resolveView('view-body', true),
            '_title' => $this->_getTitle('view'),
        ];

        return $data;
    }

    public function actionViewJson($id)
    {
        \Yii::$app->response->format = 'json';
        $element = $this->_getElement($id)->toArray();
        return json_encode($element, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Page to create the element.
     *
     * - creates a model
     *
     * - title "create" is used
     *
     * - javascript:
     *   - create
     *   - form
     *  is used
     *
     * - data for view gets through the method _getCreateData
     *
     * - following views is used:
     *   - create.php - shell
     *   - create-body.php - form-shell
     *   - form.php - form (share between create and update)
     *
     * @return string html-code of the page
     */
    public function actionCreate()
    {
        $modelClass = $this->modelClass;

        $element = new $modelClass;
        $element->load(\Yii::$app->request->get());
        $this->_save($element);

        $this->_registerJs('create');
        $this->_registerJs('form');

        $viewData = $this->_getCreateData($element);
        return $this->render($this->_resolveView('create'), $viewData);
    }

    /**
     * Page to create the element.
     *
     * - creates a model
     *
     * - title "create" is used
     *
     * - javascript:
     *   - create
     *   - form
     *  is used
     *
     * - data for view gets through the method _getCreateData
     *
     * - following views is used:
     *   - create.php - shell
     *   - create-body.php - form-shell
     *   - form.php - form (share between create and update)
     *
     * @return string html-code of the page
     */
    public function actionCreateJson()
    {
        $modelClass = $this->modelClass;
        $element = new $modelClass;
        $result = (
            $element->load(\yii::$app->request->post()) &&
            $element->save()
        );

        return json_encode(
            [$result, $element->getErrors()],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Get data for actionCreate
     * @param  mixed $element element
     * @return array data for actionCreate
     */
    protected function _getCreateData($element)
    {
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

    /**
     * Update the element.
     *
     * - updates the model
     *
     * - title "update" is used
     *
     *  - javascript:
     *   - update
     *   - form
     *  is used
     *
     * - data for view gets through the method _getUpdateData
     *
     * - following views is used:
     *   - update.php - shell
     *   - update-body.php - form-shell
     *   - form.php - form (share between create and update)
     *
     * @param  int $id element ID
     * @return string html-code of the page
     */
    public function actionUpdate($id)
    {
        $element = $this->_getElement($id);
        $this->_save($element);

        $this->_registerJs('update');
        $this->_registerJs('form');
        $viewData = $this->_getUpdateData($element);
        return $this->render($this->_resolveView('update'), $viewData);
    }

    /**
     * Update the element.
     *
     * - updates the model
     *
     * - title "update" is used
     *
     *  - javascript is used:
     *   - update
     *   - form
     *
     * - data for view gets through the method _getUpdateData
     *
     * - following views is used:
     *   - update.php - shell
     *   - update-body.php - form-shell
     *   - form.php - form (share between create and update)
     *
     * @param  int $id element ID
     * @return string html-code of the page
     */
    public function actionUpdateJson($id)
    {
        $element = $this->_getElement($id);
        $result = (
            $element->load(\yii::$app->request->post()) &&
            $element->save()
        );

        return json_encode(
            [$result, $element->getErrors()],
            JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Get data for actionUpdate
     * @param  mixed $element element
     * @return array data for actionUpdate
     */
    public function _getUpdateData($element)
    {
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

    /**
     * Delete element
     * @param  int $id element ID
     */
    public function actionDelete($id)
    {
        $this->_getElement($id)->delete();
        $this->_redirectBack();
    }

    /**
     * Get filtered data list
     * @return \yii\data\ActiveDataProvider data list
     */
    protected function _getList()
    {
        $query = $this->_getQuery();

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

    protected function _getQuery() {
        $result = \idfly\components\QueryBuilder::build(
            $this->modelClass,
            \yii::$app->request->get('query', []), [
                'allowedKeys' => $this->allowedQueryKeys,
            ]
        );

        return $result;
    }

    /**
     * Get the form element field; allows to selectively override the field
     * of form for element in derived class as follows:
     *   return [
     *       'password' => $form->field($element, 'property')->input('password')
     *   ];
     * @param  \yii\widgets\ActiveForm $form form
     * @param  mixed $element element
     * @return array
     */
    public function getFieldsInputs($form, $element)
    {
        return [];
    }

    /**
     * Get the title of the section
     * @param  string $type type of section
     * @return string section title
     */
    protected function _getTitle($type)
    {
        if(!empty($this->titles[$type])) {
            return $this->titles[$type];
        }

        return $this->_getKey() . ': ' . $type;
    }

    /**
     * Get the controller ID for usage in variables names
     * @param  boolean $many in plural
     * @param  boolean $dashCase
     * @return string
     */
    protected function _getKey($many = true, $dashCase = false)
    {
        $key = preg_replace('{^.*?\\\\([^\\\\]+)Controller$}', '$1',
            self::className());

        if($dashCase) {
            $key = preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $key);
        }

        $key = strtolower($key);

        if(!$many) {
            $key = preg_replace('{s$}', '', $key);
        }

        return $key;
    }

    /**
     * Save the element; executes only in a post-query; if element was saved
     * will be a transition to the previous page or the page with data list
     *
     * @param  mixed $element element for saving
     * @return boolean
     */
    protected function _save($element)
    {
        if(!\Yii::$app->request->isPost) {
            return;
        }

        $element->load(\Yii::$app->request->post());
        if($element->save()) {
            $this->_lastSavedElement = $element;
            $this->_redirectBack();
            return true;
        }

        return false;
    }

    /**
     * Find the view and return it's path
     * @param  string $view view ID (for example, index or update)
     * @param  boolean $fullPath returns full path to view
     * @return string
     */
    protected function _resolveView($view, $fullPath = false)
    {
        $path = $this->module->getViewPath() . '/' .
            $this->_getKey(true, true) . '/' . $view . '.php';

        if(file_exists($path)) {
            if($fullPath) {
                return $path;
            }

            return $view;
        }

        $basePath = \yii::getAlias('@app');
        $path = $this->viewPath . $view . '.php';

        if(file_exists($basePath . '/' . $path)) {
            if($fullPath) {
                return $basePath . '/' . $path;
            }

            return '@app/' . $path;
        }

        $basePath = \yii::getAlias('@vendor');
        $path = 'idfly/yii2-components/views/admin/' . $view . '.php';

        if(file_exists($basePath . '/' . $path)) {
            if($fullPath) {
                return $basePath . '/' . $path;
            }

            return '@vendor/' . $path;
        }

        if($fullPath) {
            return null;
        }

        return $view;
    }

    /**
     * Register the js function for execution
     * @param  string $type function type
     */
    protected function _registerJs($type)
    {
        if(empty($this->js[$type])) {
            return;
        }

        $this->view->registerJs($this->js[$type]);
    }

}