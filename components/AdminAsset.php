<?php

namespace idfly\components;

/**
 * Ассеты администратора; подключает admin.css из папки assets, зависит от
 * UtilityAsset.
 *
 * В admin.css всё просто, можно просто прочитать файл, чтобы понять, что он
 * делает.
 */
class AdminAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
        'admin.css',
    ];

    public $js = [
    ];

    public $depends = [
        'idfly\components\UtilityAsset',
    ];
}
