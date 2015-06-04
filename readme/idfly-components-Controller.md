idfly\components\Controller
===============

Базовый контроллер; добавляет два protected-метода в контроллеры:

_redirectBack($default = null) -

_getElement($id, $class = null) - получить элемент с указанным идентификатором
указанного класса; если класс не указан, тогда для поиска будет использован
$this->modelClass; если $id пуст или  элемент не найден, тогда класс выбросит
\yii\web\HttpException(404)


* Class name: Controller
* Namespace: idfly\components
* Parent class: yii\web\Controller





Properties
----------


### $modelClass

    protected \idfly\components\[type] $modelClass

Класс модели, с которым работет контроллер



* Visibility: **protected**


Methods
-------


### _redirectBack

    \idfly\components\yii\web\Response idfly\components\Controller::_redirectBack(string $default)

Вернуть пользователя назад, если параметр _redirect установлен в
get-параметрах;

Если параметра _redirect нет, тогда будет использован роут $default из
аргументов

Если $default равен null, тогда будет исполльзован путь
/[module]/[controller]/[index]

* Visibility: **protected**


#### Arguments
* $default **string** - &lt;p&gt;путь по умолчанию для перенаправления пользователя&lt;/p&gt;



### _getElement

    \yii\base\Model idfly\components\Controller::_getElement(integer $id, string $class)

Найти элемент с заданным id и заданным классом; если элемент не найден,
то будет выброшено исключение; если класс не задан, то будет использован
класс по умолчанию ($modelClass). Если оба класса не заданы, то будет
выброшено исключение.



* Visibility: **protected**


#### Arguments
* $id **integer** - &lt;p&gt;идентификатор&lt;/p&gt;
* $class **string** - &lt;p&gt;класс&lt;/p&gt;


