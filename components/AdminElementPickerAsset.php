<?php

namespace idfly\components;

/**
 * DatePicker assets
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
