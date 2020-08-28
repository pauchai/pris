<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/13/19
 * Time: 1:36 PM
 */

namespace vova07\videos;


use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class Asset extends AssetBundle
{
    public $sourcePath = '@vova07/videos/assets';
    public $js = [
        'js/video.js',
        'js/jquery.highlight.js',
    ];
    public $depends = [
        JqueryAsset::class
    ];

    public function init()
    {

    }

}