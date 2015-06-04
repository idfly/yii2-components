<?php

namespace idfly\components;

class UtilityAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
        'utility.css',
    ];
}
