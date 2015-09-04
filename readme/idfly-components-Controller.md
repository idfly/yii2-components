idfly\components\Controller
===============

Basic controller; adds two protected-methods into controllers:

- _redirectBack($default = null)
  - _getElement($id, $class = null)

\yii\web\HttpException(404)


* Class name: Controller
* Namespace: idfly\components
* Parent class: yii\web\Controller





Properties
----------


### $modelClass

    protected \idfly\components\[type] $modelClass

Model’s class, which is operated by controllers.



* Visibility: **protected**


Methods
-------


### _redirectBack

    \idfly\components\yii\web\Response idfly\components\Controller::_redirectBack(string $default)

Return the user back, if the _redirect parameter is set in
Get-parameters;

If there is no _redirect parameter, then the $default route from
the arguments will be used.

If $ default is null, then the path will be used
/[module]/[controller]/[index]

* Visibility: **protected**


#### Arguments
* $default **string** - &lt;p&gt;default path for forwarding user&lt;/p&gt;



### _getElement

    \yii\base\Model idfly\components\Controller::_getElement(integer|array $id, string $class)

Find the element with the given `id`, and `class`; if the element is
not found, the exception will be thrown; If a class is not indicated,
there will be used the default class ($modelClass). If the both
classes are not set, the exception will be thrown.



* Visibility: **protected**


#### Arguments
* $id **integer|array** - &lt;p&gt;id or case &lt;code&gt;where&lt;/code&gt; by which the search should
be done as [&#039;key&#039; =&gt; &#039;value&#039;]&lt;/p&gt;
* $class **string** - &lt;p&gt;class&lt;/p&gt;



### registerMetaTags

    mixed idfly\components\Controller::registerMetaTags(\yii\base\View $view, array $metaArray)

Fill in the meta-tags of the page



* Visibility: **public**
* This method is **static**.


#### Arguments
* $view **yii\base\View** - &lt;p&gt;view object&lt;/p&gt;
* $metaArray **array** - &lt;p&gt;array with data&lt;/p&gt;


