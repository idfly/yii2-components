idfly\components\AuthorizationForm
===============

Форма авторизации пользователя; использование:

$authorizationForm = new \idfly\components\AuthorizationForm();
$authorizationForm->load(\yii::$app->request->post());
$success = $authorizationForm->login();

Авторизация проходит по полю login и модели \app\models\User, для
кастомные параметры можно указать в конструкторе:

$authorizationForm = new \idfly\components\AuthorizationForm([
   'loginField' => 'email',
   'modelClass' => 'app\models\Admin',
]);


* Class name: AuthorizationForm
* Namespace: idfly\components
* Parent class: yii\base\Model





Properties
----------


### $password

    public mixed $password





* Visibility: **public**


### $loginField

    public mixed $loginField = 'login'





* Visibility: **public**


### $modelClass

    public mixed $modelClass = 'app\models\User'





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

    mixed idfly\components\AuthorizationForm::login()





* Visibility: **public**



