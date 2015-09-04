<?php

namespace idfly\components;

/**
 * Modal window asset; includes the `utility-modal.css` and `utility-modal.js`
 * from `assets` folder.
 */
class ModalAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
        'modal.css',
    ];

    public $js = [
        'modal.js',
    ];

    public $depends = [
    ];
}
