<?php

namespace idfly\components;

/**
 * Утилитные ассаты; подключает utility.css.
 *
 * В utility.css всё просто, можно просто прочитать файл, чтобы понять, что он
 * делает.
 */
class UtilityAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
        'utility.css',
    ];
}