<?php

namespace devleaks\recurinput;

use yii\web\AssetBundle;

class RRuleAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/rrule/lib';

    public $js = [
        'rrule.js',
    	'nlp.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ]; 
}
