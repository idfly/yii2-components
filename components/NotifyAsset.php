<?php

namespace idfly\components;

/**
 * Ассеты модального окна; подключает utility-notify.css и utility-notify.js из
 * папки assets.
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
