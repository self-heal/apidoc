<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yangsl\apidoc;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by Gii.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ApidocAsset extends AssetBundle
{
    public $sourcePath = '@yangsl/test/assets';
    public $css = [
        'main.css',
        'https://cdn.bootcss.com/semantic-ui/2.2.2/semantic.min.css',
    ];
    public $js = [
        "https://cdn.bootcss.com/jquery/1.11.3/jquery.min.js",
        "https://cdn.bootcss.com/semantic-ui/2.2.2/semantic.min.js"
    ];
    public $depends = [
    ];
}
