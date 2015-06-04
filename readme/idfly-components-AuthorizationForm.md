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


### $loginField

    public string $loginField = 'login'

Поле модели, которое будет использоваться для логина.



* Visibility: **public**


### $modelClass

    public string $modelClass = 'app\models\User'

Класс модели, который будет использоваться для логина. Модель должна
подключать трейт Authorization или имплементировать интерфейс этого
трейта.



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

Выполнить авторизацию.



* Visibility: **public**



