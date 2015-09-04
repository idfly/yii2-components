<?php

namespace idfly\components;

/**
 * Asset modal window; connects the `utility-notify.css` and `utility-notify.js`
 * from `assets` folder.
 */
class NotifyAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
        'notify.css',
    ];

    public $js = [
        'notify.js',
    ];

    public $depends = [
    ];
}
