<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace idfly\components;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AdminAsset extends AssetBundle
{
    public $sourcePath = '@vendor/idfly/yii2-components/assets';

    public $css = [
        'utility.css',
        'admin.css',
    ];

    public $js = [
    ];

    public $depends = [
        'idfly\porto\PortoAsset',
    ];
}
