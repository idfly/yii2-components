<?php

namespace idfly\components;

/**
 * DatePicker assets
 */
class AdminDatePickerAsset extends \yii\web\AssetBundle
{
    /** @inheritdoc */
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
    ];

    public $js = [
        'admin_date_picker.js',
    ];

    public $depends = [
        'yii\jui\JuiAsset',
    ];
}
