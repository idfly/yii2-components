idfly\components\AdminController
===============

Admin panel Controller; adds basic operations (list, view, add, update,
delete) to the controllers, which are inherited from it.

All templates resolves by following rules:

 1. Needed template is searched in views/[controller] folder
 2. If it is not found, you may use admin/views/layouts/admin folder
     (overridden in the variable $viewPath)
 3. If it is not found, you may use vendor/idfly/yii2-components/views/admin
     folder

The page title is taken from the array protected $titles.

Javascript of the page is taken from array protected $js.

The controller works with a model, indicated in the variable
protected $modelClass.

Adds 5 actions to the controller:
  - actionIndex
  - actionView
  - actionCreate
  - actionUpdate
  - actionDelete

To default view is displayed normally you need to include AdminAsset in
assets.


* Class name: AdminController
* Namespace: idfly\components
* Parent class: [idfly\components\Controller](idfly-components-Controller.md)





Properties
----------


### $layout

    public string $layout = 'admin.php'

Ссылка на layout



* Visibility: **public**


### $allowedQueryKeys

    protected array $allowedQueryKeys = null

List of keys which gives the access to search on query through "AND";
the example of array: ['date?>', 'price?<=', 'name?'] (converts the
query: `date` more value, `price` less or equal, `name` is equal to
the value)

If GET receives NULL value when the query will be look like IS NULL,
if it receives NOT NULL while "IS NOT NULL" for the empty operator
('name?')

* Visibility: **protected**


### $titles

    protected array $titles = array()

The section titles; the possible values of the keys array:
  - index
  - view
  - create
  - update



* Visibility: **protected**


### $js

    protected array $js = array()

javascript, which is needed to execute for some section;
the possible values of the keys array:
  - index
  - view
  - create
  - form
  - update



* Visibility: **protected**


### $_lastSavedElement

    protected mixed $_lastSavedElement

last saved (added or updated) element



* Visibility: **protected**


### $viewPath

    protected string $viewPath = 'admin/views/layouts/admin'

path for custom views



* Visibility: **protected**


### $modelClass

    protected \idfly\components\[type] $modelClass

Model’s class, which is operated by controllers.



* Visibility: **protected**


Methods
-------


### actionIndex

    string idfly\components\AdminController::actionIndex()

Display the list of elements.

- displays the list of models

- the list of models getting from the method  _getList

- the search of models is automatically produces based on
GET-parameters and variables of queryKeys, searchKeys classes (see
phpdoc to $queryKeys and $searchKeys)

- title "index" is used

- javascript "index" is used

- following views is used:
  - index.php - shell
  - index-list.php - the list of models
  - index-footer.php - the footer of the list (pagination)

* Visibility: **public**




### actionListJson

    mixed idfly\components\AdminController::actionListJson()





* Visibility: **public**




### _render

    string idfly\components\AdminController::_render(string $view, array $data)

page render; if ajax-request calls - render will be promoted without
layout



* Visibility: **protected**


#### Arguments
* $view **string** - &lt;p&gt;view-file&lt;/p&gt;
* $data **array** - &lt;p&gt;data for view&lt;/p&gt;



### actionView

    string idfly\components\AdminController::actionView(integer $id)

View element.

- displays the page of model view

- title "view" is used

- javascript "view" is used

- data for view gets through the method _getViewData

- following views is used:
  - view.php - shell
  - view-body.php - contents

* Visibility: **public**


#### Arguments
* $id **integer** - &lt;p&gt;element ID&lt;/p&gt;



### _getViewData

    mixed idfly\components\AdminController::_getViewData($element)





* Visibility: **protected**


#### Arguments
* $element **mixed**



### actionViewJson

    mixed idfly\components\AdminController::actionViewJson($id)





* Visibility: **public**


#### Arguments
* $id **mixed**



### actionCreate

    string idfly\components\AdminController::actionCreate()

Page to create the element.

- creates a model

- title "create" is used

- javascript:
  - create
  - form
 is used

- data for view gets through the method _getCreateData

- following views is used:
  - create.php - shell
  - create-body.php - form-shell
  - form.php - form (share between create and update)

* Visibility: **public**




### actionCreateJson

    string idfly\components\AdminController::actionCreateJson()

Page to create the element.

- creates a model

- title "create" is used

- javascript:
  - create
  - form
 is used

- data for view gets through the method _getCreateData

- following views is used:
  - create.php - shell
  - create-body.php - form-shell
  - form.php - form (share between create and update)

* Visibility: **public**




### _getCreateData

    array idfly\components\AdminController::_getCreateData(mixed $element)

Get data for actionCreate



* Visibility: **protected**


#### Arguments
* $element **mixed** - &lt;p&gt;element&lt;/p&gt;



### actionUpdate

    string idfly\components\AdminController::actionUpdate(integer $id)

Update the element.

- updates the model

- title "update" is used

 - javascript:
  - update
  - form
 is used

- data for view gets through the method _getUpdateData

- following views is used:
  - update.php - shell
  - update-body.php - form-shell
  - form.php - form (share between create and update)

* Visibility: **public**


#### Arguments
* $id **integer** - &lt;p&gt;element ID&lt;/p&gt;



### actionUpdateJson

    string idfly\components\AdminController::actionUpdateJson(integer $id)

Update the element.

- updates the model

- title "update" is used

 - javascript is used:
  - update
  - form

- data for view gets through the method _getUpdateData

- following views is used:
  - update.php - shell
  - update-body.php - form-shell
  - form.php - form (share between create and update)

* Visibility: **public**


#### Arguments
* $id **integer** - &lt;p&gt;element ID&lt;/p&gt;



### _getUpdateData

    array idfly\components\AdminController::_getUpdateData(mixed $element)

Get data for actionUpdate



* Visibility: **public**


#### Arguments
* $element **mixed** - &lt;p&gt;element&lt;/p&gt;



### actionDelete

    mixed idfly\components\AdminController::actionDelete(integer $id)

Delete element



* Visibility: **public**


#### Arguments
* $id **integer** - &lt;p&gt;element ID&lt;/p&gt;



### _getList

    \yii\data\ActiveDataProvider idfly\components\AdminController::_getList()

Get filtered data list



* Visibility: **protected**




### _getQuery

    mixed idfly\components\AdminController::_getQuery()





* Visibility: **protected**




### getFieldsInputs

    array idfly\components\AdminController::getFieldsInputs(\yii\widgets\ActiveForm $form, mixed $element)

Get the form element field; allows to selectively override the field
of form for element in derived class as follows:
  return [
      'password' => $form->field($element, 'property')->input('password')
  ];



* Visibility: **public**


#### Arguments
* $form **yii\widgets\ActiveForm** - &lt;p&gt;form&lt;/p&gt;
* $element **mixed** - &lt;p&gt;element&lt;/p&gt;



### _getTitle

    string idfly\components\AdminController::_getTitle(string $type)

Get the title of the section



* Visibility: **protected**


#### Arguments
* $type **string** - &lt;p&gt;type of section&lt;/p&gt;



### _getKey

    string idfly\components\AdminController::_getKey(boolean $many)

Get the controller ID for usage in variables names



* Visibility: **protected**


#### Arguments
* $many **boolean** - &lt;p&gt;in plural&lt;/p&gt;



### _save

    boolean idfly\components\AdminController::_save(mixed $element)

Save the element; executes only in a post-query; if element was saved
will be a transition to the previous page or the page with data list



* Visibility: **protected**


#### Arguments
* $element **mixed** - &lt;p&gt;element for saving&lt;/p&gt;



### _resolveView

    string idfly\components\AdminController::_resolveView(string $view, boolean $fullPath)

Find the view and return it's path



* Visibility: **protected**


#### Arguments
* $view **string** - &lt;p&gt;view ID (for example, index or update)&lt;/p&gt;
* $fullPath **boolean** - &lt;p&gt;returns full path to view&lt;/p&gt;



### _registerJs

    mixed idfly\components\AdminController::_registerJs(string $type)

Register the js function for execution



* Visibility: **protected**


#### Arguments
* $type **string** - &lt;p&gt;function type&lt;/p&gt;



### _redirectBack

    \idfly\components\yii\web\Response idfly\components\Controller::_redirectBack(string $default)

Return the user back, if the _redirect parameter is set in
Get-parameters;

If there is no _redirect parameter, then the $default route from
the arguments will be used.

If $ default is null, then the path will be used
/[module]/[controller]/[index]

* Visibility: **protected**
* This method is defined by [idfly\components\Controller](idfly-components-Controller.md)


#### Arguments
* $default **string** - &lt;p&gt;default path for forwarding user&lt;/p&gt;



### _getElement

    \yii\base\Model idfly\components\Controller::_getElement(integer|array $id, string $class)

Find the element with the given `id`, and `class`; if the element is
not found, the exception will be thrown; If a class is not indicated,
there will be used the default class ($modelClass). If the both
classes are not set, the exception will be thrown.



* Visibility: **protected**
* This method is defined by [idfly\components\Controller](idfly-components-Controller.md)


#### Arguments
* $id **integer|array** - &lt;p&gt;id or case &lt;code&gt;where&lt;/code&gt; by which the search should
be done as [&#039;key&#039; =&gt; &#039;value&#039;]&lt;/p&gt;
* $class **string** - &lt;p&gt;class&lt;/p&gt;



### registerMetaTags

    mixed idfly\components\Controller::registerMetaTags(\yii\base\View $view, array $metaArray)

Fill in the meta-tags of the page



* Visibility: **public**
* This method is **static**.
* This method is defined by [idfly\components\Controller](idfly-components-Controller.md)


#### Arguments
* $view **yii\base\View** - &lt;p&gt;view object&lt;/p&gt;
* $metaArray **array** - &lt;p&gt;array with data&lt;/p&gt;


