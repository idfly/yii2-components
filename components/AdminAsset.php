<?php

namespace idfly\components;

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
        'idfly\porto\PortoAsset',
        'idfly\components\UtilityAsset',
    ];
}
