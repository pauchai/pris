<?php

namespace devleaks\recurinput;

use yii\web\AssetBundle;

class RecurInputAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/jquery.recurrenceinput.js';

	public $js = [
	    'lib/jquery.tmpl.js',
		'lib/jquery.tools.dateinput.js',
		'lib/jquery.tools.overlay.js',
	    'src/jquery.recurrenceinput.js',
	 ];

	public $css = [
	     'src/jquery.recurrenceinput.css',
	 ];
}
