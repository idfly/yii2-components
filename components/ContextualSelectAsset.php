<?php

namespace idfly\components;

/**
 * DatePicker assets
 */
class ContextualSelectAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
    ];

    public $js = [
        'contextual_select.js',
    ];

    public $depends = [
    ];
}
