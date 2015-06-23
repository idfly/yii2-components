<?php

namespace idfly\components;

/**
 * Ассеты дэйтпикера
 */
class AdminElementPickerAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
    ];

    public $js = [
        'admin_element_picker.js',
    ];

    public $depends = [
    ];
}
