idfly\components\AdminController
===============

Админ контроллер; добавляет базовы операции (список, просмотр, добавление,
изменение, удаление) в контроллеры, которые наследуются от него.

Все шаблоны резолвятся по следующим правилам:

  1. Нужный шаблон ищется в папке views/[controller]
  2. Если не найдено, то используется папка admin/views/layouts/admin
     (значение может быть переопределено в переменной $viewPath)
  3. Если не найдено, то используется папка vendor/idfly/yii2-components/views/admin

Заголовок страницы берётся из массива protected $titles.

Javascript страницы берётся из массива protected $js.

Контроллер работает с моделью, указанной в переменной protected $modelClass.

Добавляет 5 action'ов в контроллер:
  - actionIndex
  - actionView
  - actionCreate
  - actionUpdate
  - actionDelete


* Class name: AdminController
* Namespace: idfly\components
* Parent class: [idfly\components\Controller](idfly-components-Controller.md)





Properties
----------


### $layout

    public string $layout = 'admin.php'

Ссылка на layout



* Visibility: **public**


### $queryKeys

    protected array $queryKeys = array()

Список ключей, по которым доступен поиск по запросу через "или"; пример:
['first_name', 'last_name'] (будет преобразовано в запрос:
first_name LIKE значение% или last_name LIKE значение% при поиске моделей



* Visibility: **protected**


### $searchKeys

    protected array $searchKeys = array()

Список ключей, по которым доступен поиск по запросу через "и"; пример
массива: ['date?>', 'price?<=', 'name?'] (преобразует в запрос: date
больше значения и price меньше либо равна значению и name равно значению)

Если в GET придёт значение NULL, тогда запрос будет выглядеть как IS NULL,
если придёт NOTNULL тогда как "IS NOT NULL" для пустого оператора
('name?')

* Visibility: **protected**


### $titles

    protected array $titles = array()

Заголовки разделов; возможные значения ключей массива:
  - index
  - view
  - create
  - update



* Visibility: **protected**


### $js

    protected array $js = array()

javascript, который нужно выполнить для того, или иного раздела;
возможные значения ключей массива:
  - index
  - view
  - create
  - form
  - update



* Visibility: **protected**


### $_lastSavedElement

    protected mixed $_lastSavedElement

последний сохранённый (добавленный или обновлённый) элемент



* Visibility: **protected**


### $viewPath

    protected string $viewPath = 'admin/views/layouts/admin'

Путь для кастомных view



* Visibility: **protected**


Methods
-------


### actionIndex

    string idfly\components\AdminController::actionIndex()

Отобразить список элементов.

- отображает список моделей

- список моделей получается из метода _getList

- поиск моделей производится автоматически на основе GET-параметров и
  значений переменных классов queryKeys, searchKeys (см. phpdoc к
  $queryKeys и $searchKeys)

- используется заголовок "index"

- используется javascript "index"

- используются следующие view:
  - index.php - оболочка
  - index-list.php - список моделей
  - index-footer.php - футер списка (постраничная навигация)

* Visibility: **public**




### _render

    string idfly\components\AdminController::_render(string $view, array $data)

Произвести рендер страницы; если вызывается аякс запрос - рендер будет
произведён без layout'а



* Visibility: **protected**


#### Arguments
* $view **string** - &lt;p&gt;view-файл&lt;/p&gt;
* $data **array** - &lt;p&gt;данные для view&lt;/p&gt;



### actionView

    string idfly\components\AdminController::actionView(integer $id)

Просмотреть элемент.

- отображает страницу просмотра модели

- используется title "view"

- используется javascript "view"

- данные для view получаются через метод _getViewData

- используются следующие view:
  - view.php - оболочка
  - view-body.php - внутренности

* Visibility: **public**


#### Arguments
* $id **integer** - &lt;p&gt;идентификатор элемента&lt;/p&gt;



### _getViewData

    mixed idfly\components\AdminController::_getViewData($element)





* Visibility: **public**


#### Arguments
* $element **mixed**



### actionCreate

    string idfly\components\AdminController::actionCreate()

Страница создания элемента.

- создаёт модель

- используется title "create"

- используется javascript:
  - create
  - form

- данные для view получаются через метод _getCreateData

- используются следующие view:
  - create.php - оболочка
  - create-body.php - оболочка формы
  - form.php - форма (шэрится между create и update)

* Visibility: **public**




### _getCreateData

    array idfly\components\AdminController::_getCreateData(mixed $element)

Получить данные для actionCreate



* Visibility: **protected**


#### Arguments
* $element **mixed** - &lt;p&gt;элемент&lt;/p&gt;



### actionUpdate

    string idfly\components\AdminController::actionUpdate(integer $id)

Обновить элемент.

- обновляет модель

- используется title "update"

- используется javascript:
  - update
  - form

- данные для view получаются через метод _getUpdateData

- используются следующие view:
  - update.php - оболочка
  - update-body.php - оболочка формы
  - form.php - форма (шэрится между create и update)

* Visibility: **public**


#### Arguments
* $id **integer** - &lt;p&gt;идентификатор элемента&lt;/p&gt;



### _getUpdateData

    array idfly\components\AdminController::_getUpdateData(mixed $element)

Получить данные для actionUpdate.



* Visibility: **public**


#### Arguments
* $element **mixed** - &lt;p&gt;элемент&lt;/p&gt;



### actionDelete

    mixed idfly\components\AdminController::actionDelete(integer $id)

Удалить элемент



* Visibility: **public**


#### Arguments
* $id **integer** - &lt;p&gt;идентификатор элемента&lt;/p&gt;



### _getList

    \yii\data\ActiveDataProvider idfly\components\AdminController::_getList()

Получить отфильтрованный список данных



* Visibility: **protected**




### getFieldsInputs

    array idfly\components\AdminController::getFieldsInputs(\yii\widgets\ActiveForm $form, mixed $element)

Получить поля формы элемента; позволяет выборочно переопределить поля
формы для элемента в дочернем классе следующим образом:
  return [
      'password' => $form->field($element, 'property')->input('password')
  ];



* Visibility: **public**


#### Arguments
* $form **yii\widgets\ActiveForm** - &lt;p&gt;форма&lt;/p&gt;
* $element **mixed** - &lt;p&gt;элемент&lt;/p&gt;



### _getTitle

    string idfly\components\AdminController::_getTitle(string $type)

Получить заголовок раздела



* Visibility: **protected**


#### Arguments
* $type **string** - &lt;p&gt;тип раздела&lt;/p&gt;



### getQuery

    \yii\db\ActiveQuery idfly\components\AdminController::getQuery(\yii\db\ActiveQuery $query, array $get)

Получить запрос для выбора элементов



* Visibility: **public**


#### Arguments
* $query **yii\db\ActiveQuery** - &lt;p&gt;запрос для модификации; если null -
будет создан новый запрос&lt;/p&gt;
* $get **array** - &lt;p&gt;данные из get запроса; если null - будет использован
$_GET&lt;/p&gt;



### _getSearchQuery

    \yii\db\ActiveQuery idfly\components\AdminController::_getSearchQuery(string $table, \yii\db\ActiveQuery $query, array $get, array $searchKeys, array $queryKeys)

Создать запрос для фильтрации элементов



* Visibility: **protected**


#### Arguments
* $table **string** - &lt;p&gt;таблица&lt;/p&gt;
* $query **yii\db\ActiveQuery** - &lt;p&gt;запрос, в который устновить условия
поиска&lt;/p&gt;
* $get **array** - &lt;p&gt;данные, на основе которых устновить запрос&lt;/p&gt;
* $searchKeys **array** - &lt;p&gt;ключи массива данных, которые можно использовать
для составления запроса&lt;/p&gt;
* $queryKeys **array** - &lt;p&gt;поля таблицы, в которых необходимо производвить
или-поиск по запросу $get[&#039;query&#039;]&lt;/p&gt;



### _getLikeQuery

    \yii\db\ActiveQuery idfly\components\AdminController::_getLikeQuery(\yii\db\ActiveQuery $query, array $queryKeys)

Получить запрос для или-фильтрации по списку полей



* Visibility: **protected**


#### Arguments
* $query **yii\db\ActiveQuery** - &lt;p&gt;[description]&lt;/p&gt;
* $queryKeys **array** - &lt;p&gt;ключи, по которым можно фильтровать&lt;/p&gt;



### _getKey

    string idfly\components\AdminController::_getKey(boolean $many)

Получить идентификатор контроллера для использования в названиях
переменных



* Visibility: **protected**


#### Arguments
* $many **boolean** - &lt;p&gt;во множественном числе&lt;/p&gt;



### _save

     idfly\components\AdminController::_save(mixed $element)

Сохранить элемент; выполняется только в пост-запросе; если элемент
был сохранён, произойдёт переход на предыдущуюю страницу или страницу
списка данных



* Visibility: **protected**


#### Arguments
* $element **mixed** - &lt;p&gt;элемент для сохранения&lt;/p&gt;



### _resolveView

    string idfly\components\AdminController::_resolveView(string $view, boolean $fullPath)

Найти view и вернуть его путь



* Visibility: **protected**


#### Arguments
* $view **string** - &lt;p&gt;идентификатор view (например, index или update)&lt;/p&gt;
* $fullPath **boolean** - &lt;p&gt;веруть полный путь к view&lt;/p&gt;



### _registerJs

    mixed idfly\components\AdminController::_registerJs(string $type)

Зарегестрировать функцию js для выполнения



* Visibility: **protected**


#### Arguments
* $type **string** - &lt;p&gt;тип функции&lt;/p&gt;



### _redirectBack

    mixed idfly\components\Controller::_redirectBack($default)





* Visibility: **protected**
* This method is defined by [idfly\components\Controller](idfly-components-Controller.md)


#### Arguments
* $default **mixed**



### _getElement

    mixed idfly\components\Controller::_getElement($id, $class)





* Visibility: **protected**
* This method is defined by [idfly\components\Controller](idfly-components-Controller.md)


#### Arguments
* $id **mixed**
* $class **mixed**


