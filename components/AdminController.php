<?php

namespace idfly\components;

use yii\helpers\ArrayHelper;

/**
 * Админ контроллер; добавляет базовы операции (список, просмотр, добавление,
 * изменение, удаление) в контроллеры, которые наследуются от него.
 *
 * Все шаблоны резолвятся по следующим правилам:
 *
 *   1. Нужный шаблон ищется в папке views/[controller]
 *   2. Если не найдено, то используется папка admin/views/layouts/admin
 *      (значение может быть переопределено в переменной $viewPath)
 *   3. Если не найдено, то используется папка vendor/idfly/yii2-components/views/admin
 *
 * Заголовок страницы берётся из массива protected $titles.
 *
 * Javascript страницы берётся из массива protected $js.
 *
 * Контроллер работает с моделью, указанной в переменной protected $modelClass.
 *
 * Добавляет 5 action'ов в контроллер:
 *   - actionIndex
 *   - actionView
 *   - actionCreate
 *   - actionUpdate
 *   - actionDelete
 *
 * Чтобы дефолтные view нормально отображались, нужно подключить AdminAsset в
 * Asset'ы.
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
     * Список ключей, по которым доступен поиск по запросу через "или"; пример:
     * ['first_name', 'last_name'] (будет преобразовано в запрос:
     * first_name LIKE значение% или last_name LIKE значение% при поиске моделей
     * @var array
     */
    protected $queryKeys = [];

    /**
     * Список ключей, по которым доступен поиск по запросу через "и"; пример
     * массива: ['date?>', 'price?<=', 'name?'] (преобразует в запрос: date
     * больше значения и price меньше либо равна значению и name равно значению)
     *
     * Если в GET придёт значение NULL, тогда запрос будет выглядеть как IS NULL,
     * если придёт NOTNULL тогда как "IS NOT NULL" для пустого оператора
     * ('name?')
     * @var array
     */
    protected $searchKeys = [];

    /**
     * Заголовки разделов; возможные значения ключей массива:
     *   - index
     *   - view
     *   - create
     *   - update
     * @var array
     */
    protected $titles = [];

    /**
     * javascript, который нужно выполнить для того, или иного раздела;
     * возможные значения ключей массива:
     *   - index
     *   - view
     *   - create
     *   - form
     *   - update
     * @var array
     */
    protected $js = [];

    /**
     * последний сохранённый (добавленный или обновлённый) элемент
     * @var mixed
     */
    protected $_lastSavedElement;

    /**
     * Путь для кастомных view
     * @var string
     */
    protected $viewPath = 'admin/views/layouts/admin';

    /**
     * Отобразить список элементов.
     *
     * - отображает список моделей
     *
     * - список моделей получается из метода _getList
     *
     * - поиск моделей производится автоматически на основе GET-параметров и
     *   значений переменных классов queryKeys, searchKeys (см. phpdoc к
     *   $queryKeys и $searchKeys)
     *
     * - используется заголовок "index"
     *
     * - используется javascript "index"
     *
     * - используются следующие view:
     *   - index.php - оболочка
     *   - index-list.php - список моделей
     *   - index-footer.php - футер списка (постраничная навигация)
     *
     * @return string html-код страницы
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

    /**
     * Произвести рендер страницы; если вызывается аякс запрос - рендер будет
     * произведён без layout'а
     * @param  string $view view-файл
     * @param  array $data данные для view
     * @return string html-код страницы
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
     * Просмотреть элемент.
     *
     * - отображает страницу просмотра модели
     *
     * - используется title "view"
     *
     * - используется javascript "view"
     *
     * - данные для view получаются через метод _getViewData
     *
     * - используются следующие view:
     *   - view.php - оболочка
     *   - view-body.php - внутренности
     *
     * @param  int $id идентификатор элемента
     * @return string html-код страницы
     */
    public function actionView($id)
    {
        $element = $this->_getElement($id);
        $view = $this->_resolveView('view');

        $this->_registerJs('view');
        $key = $this->_getKey();
        $data = $this->_getViewData($element);

        return $this->_render($view, $data);
    }

    public function _getViewData($element)
    {
        $data = [
            $this->_getKey(false) => $element,
            '_key' => $key,
            '_keyOne' => $this->_getKey(false),
            '_element' => $element,
            '_viewBody' => $this->_resolveView('view-body', true),
            '_title' => $this->_getTitle('view'),
        ];

        return $data;
    }

    /**
     * Страница создания элемента.
     *
     * - создаёт модель
     *
     * - используется title "create"
     *
     * - используется javascript:
     *   - create
     *   - form
     *
     * - данные для view получаются через метод _getCreateData
     *
     * - используются следующие view:
     *   - create.php - оболочка
     *   - create-body.php - оболочка формы
     *   - form.php - форма (шэрится между create и update)
     *
     * @return string html-код страницы
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
     * Получить данные для actionCreate
     * @param  mixed $element элемент
     * @return array данные для actionCreate
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
     * Обновить элемент.
     *
     * - обновляет модель
     *
     * - используется title "update"
     *
     * - используется javascript:
     *   - update
     *   - form
     *
     * - данные для view получаются через метод _getUpdateData
     *
     * - используются следующие view:
     *   - update.php - оболочка
     *   - update-body.php - оболочка формы
     *   - form.php - форма (шэрится между create и update)
     *
     * @param  int $id идентификатор элемента
     * @return string html-код страницы
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
     * Получить данные для actionUpdate.
     *
     * @param  mixed $element элемент
     * @return array данные для actionUpdate
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
     * Удалить элемент
     * @param  int $id идентификатор элемента
     */
    public function actionDelete($id)
    {
        $this->_getElement($id)->delete();
        $this->_redirectBack();
    }

    /**
     * Получить отфильтрованный список данных
     * @return \yii\data\ActiveDataProvider список данных
     */
    protected function _getList()
    {
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

    /**
     * Получить поля формы элемента; позволяет выборочно переопределить поля
     * формы для элемента в дочернем классе следующим образом:
     *   return [
     *       'password' => $form->field($element, 'property')->input('password')
     *   ];
     * @param  \yii\widgets\ActiveForm $form форма
     * @param  mixed $element элемент
     * @return array
     */
    public function getFieldsInputs($form, $element)
    {
        return [];
    }

    /**
     * Получить заголовок раздела
     * @param  string $type тип раздела
     * @return string       title раздела
     */
    protected function _getTitle($type)
    {
        if(!empty($this->titles[$type])) {
            return $this->titles[$type];
        }

        return $this->_getKey() . ': ' . $type;
    }

    /**
     * Получить запрос для выбора элементов
     * @param  \yii\db\ActiveQuery $query запрос для модификации; если null -
     * будет создан новый запрос
     * @param  array $get данные из get запроса; если null - будет использован
     * $_GET
     * @return \yii\db\ActiveQuery запрос для выбора элементов
     */
    public function getQuery($query = null, $get = null)
    {
        $modelClass = $this->modelClass;

        if(empty($query)) {
            $query = $modelClass::find();
        }

        if($get === null) {
            $get = \Yii::$app->request->get();
        }

        return $this->_getSearchQuery(
            $modelClass::tableName(),
            $query,
            $get,
            !empty($this->searchKeys) ? $this->searchKeys : [],
            $this->queryKeys
        );
    }

    /**
     * Создать запрос для фильтрации элементов
     * @param  string $table      таблица
     * @param  \yii\db\ActiveQuery $query запрос, в который устновить условия
     * поиска
     * @param  array $get данные, на основе которых устновить запрос
     * @param  array $searchKeys ключи массива данных, которые можно использовать
     * для составления запроса
     * @param  array $queryKeys поля таблицы, в которых необходимо производвить
     * или-поиск по запросу $get['query']
     * @return \yii\db\ActiveQuery
     */
    protected function _getSearchQuery($table, $query, $get, $searchKeys = [],
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
            } elseif($value === 'NOTNULL') {
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
            $query->andWhere($this->_getLikeQuery($get['query'], $queryKeys));
        }

        if(empty($query->orderBy)) {
            $query->orderBy('`' . $table . '`.`id` DESC');
        };

        return $query;
    }

    /**
     * Получить запрос для или-фильтрации по списку полей
     * @param  \yii\db\ActiveQuery $query [description]
     * @param  array $queryKeys ключи, по которым можно фильтровать
     * @return \yii\db\ActiveQuery
     */
    protected function _getLikeQuery($query, $queryKeys)
    {
        $likeQuery = ['or'];
        $modelClass = $this->modelClass;
        foreach($queryKeys as $field) {
            $likeQuery[] = "`" . $modelClass::tableName() . "`.`$field` LIKE " .
                \Yii::$app->db->quoteValue($query . '%');
        }

        return $likeQuery;
    }

    /**
     * Получить идентификатор контроллера для использования в названиях
     * переменных
     * @param  boolean $many во множественном числе
     * @return string
     */
    protected function _getKey($many = true)
    {
        $key = preg_replace('{^.*?\\\\([^\\\\]+)Controller$}', '$1',
            self::className());

        $key = strtolower($key);

        if(!$many) {
            $key = preg_replace('{s$}', '', $key);
        }

        return $key;
    }

    /**
     * Сохранить элемент; выполняется только в пост-запросе; если элемент
     * был сохранён, произойдёт переход на предыдущуюю страницу или страницу
     * списка данных
     * @param  mixed $element элемент для сохранения
     * @return
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
        }
    }

    /**
     * Найти view и вернуть его путь
     * @param  string $view идентификатор view (например, index или update)
     * @param  boolean $fullPath веруть полный путь к view
     * @return string
     */
    protected function _resolveView($view, $fullPath = false)
    {
        $path = $this->module->getViewPath() . '/' . $this->_getKey() .
            '/' . $view . '.php';

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
     * Зарегестрировать функцию js для выполнения
     * @param  string $type тип функции
     */
    protected function _registerJs($type)
    {
        if(empty($this->js[$type])) {
            return;
        }

        $this->view->registerJs($this->js[$type]);
    }

}