<?php

namespace idfly\components;

/**
 * Ассеты дэйтпикера
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
