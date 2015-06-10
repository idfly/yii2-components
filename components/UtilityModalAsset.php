<?php

namespace idfly\components;

/**
 * Ассеты модального окна; подключает utility-modal.css и utility-modal.js из
 * папки assets.
 */
class AdminAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
        'utility-modal.css',
    ];

    public $js = [
        'utility-modal.js',
    ];

    public $depends = [
    ];
}
