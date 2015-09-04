<?php

namespace idfly\components;

/**
 * Utility assets; includes `utility.css`.
 *
 * In `utility.css` everything is simple, so you can simply read the file,
 * to understand what it means.
 */
class UtilityAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
        'utility.css',
    ];
}