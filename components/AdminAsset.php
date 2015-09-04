<?php

namespace idfly\components;

/**
 * Admin panel assets; include admin.css from assets folder depends on
 * UtilityAsset
 *
 * In admin.css everything is simple you may just read a file to understand what
 * it does.
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
