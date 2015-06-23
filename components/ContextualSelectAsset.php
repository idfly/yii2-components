<?php

namespace idfly\components;

/**
 * Ассеты дэйтпикера
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
