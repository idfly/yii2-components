idfly\components\AuthorizationForm
===============

User&#039;s authorization form; Usage:

$authorizationForm = new \idfly\components\AuthorizationForm();
$authorizationForm->load(\yii::$app->request->post());
$success = $authorizationForm->login();

Authorization passes through `login` field and model `\app\models\User`,
Custom parameters could be indicated in the constructor:

$authorizationForm = new \idfly\components\AuthorizationForm([
   'loginField' => 'email',
   'modelClass' => 'app\models\Admin',
]);


* Class name: AuthorizationForm
* Namespace: idfly\components
* Parent class: yii\base\Model





Properties
----------


### $loginField

    public string $loginField = 'login'

The field in the model which will be used for `login`.



* Visibility: **public**


### $modelClass

    public string $modelClass = 'app\models\User'

Model’s class, which will be used for `login`. The model should
require `Authorization` trait or implement the trait’s interface.



* Visibility: **public**


Methods
-------


### rules

    mixed idfly\components\AuthorizationForm::rules()





* Visibility: **public**




### attributeLabels

    mixed idfly\components\AuthorizationForm::attributeLabels()





* Visibility: **public**




### login

    boolean idfly\components\AuthorizationForm::login()

Authorize.



* Visibility: **public**



