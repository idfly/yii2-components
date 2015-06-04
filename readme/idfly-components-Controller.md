idfly\components\Controller
===============

Базовый контроллер; добавляет два protected-метода в контроллеры:

_redirectBack($default = null) - вернуть пользователя назад, если параметр
_redirect установлен в get-параметрах; если параметра _redirect нет, тогда
будет использован роут $default из аргументов; если $default равен null,
тогда будет исполльзован путь /[module]/[controller]/[index]

_getElement($id, $class = null) - получить элемент с указанным идентификатором
указанного класса; если класс не указан, тогда для поиска будет использован
$this->modelClass; если $id пуст или  элемент не найден, тогда класс выбросит
\yii\web\HttpException(404)


* Class name: Controller
* Namespace: idfly\components
* Parent class: yii\web\Controller







Methods
-------


### _redirectBack

    mixed idfly\components\Controller::_redirectBack($default)





* Visibility: **protected**


#### Arguments
* $default **mixed**



### _getElement

    mixed idfly\components\Controller::_getElement($id, $class)





* Visibility: **protected**


#### Arguments
* $id **mixed**
* $class **mixed**


