# yii2-components

Supporting utility Module from idfly.

## Set

1. To the project file `composer.json` add to the `require` section:

      "idfly/yii2-components": "dev-master"

2. To the `repositories` section:

      {
           "type": "git",
           "url": "git@bitbucket.org:idfly/yii2-components.git"
       }

3. Run `composer update`

4. Add this module to the project's configuration list:

      `$config['modules']['idfly'] = ['class' => 'idfly\Module'];`

Controllers
-----------

 - [Controller](readme/idfly-components-Controller.md)
 - [Admin`s Controller](readme/idfly-components-AdminController.md)

Authorization
-----------
 - [Trait](components/Authorization.php)
 - [Form](readme/idfly-components-AuthorizationForm.md)

Helpers
-------

 - [Date](readme/idfly-components-DateHelper.md)
 - [Geo](readme/idfly-components-GeoHelper.md)
 - [Numbers](readme/idfly-components-NumberHelper.md)
 - [Password](readme/idfly-components-PasswordHelper.md)
 - [Phone](readme/idfly-components-PhoneHelper.md)

Javascript
----------

 - [Modal Window](readme-extra/utility-modal.md)

Assets
------

 - [Admin](readme/idfly-components-AdminAsset.md)
 - [Modal Window](readme/idfly-components-UtilityModalAsset.md)
 - [Utilities](readme/idfly-components-UtilityAsset.md)