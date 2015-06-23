<?php

namespace idfly\components;

/**
 * Ассеты модального окна; подключает utility-modal.css и utility-modal.js из
 * папки assets.
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
