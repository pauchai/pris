<?php

namespace devleaks\recurinput;

use yii\web\AssetBundle;

class JqueryRecurinputAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/jquery.recurrenceinput.js/src';

	public $js = [
	     'jquery.recurrenceinput.js',
	 ];

	public $css = [
	     'jquery.recurrenceinput.css',
	 ];
}
