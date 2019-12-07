<?php

namespace vova07\themes\adminlte2;

use Yii;

/**
 * Class Theme
 * @package vova07\themes\admin
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@backend/views' => '@vova07/themes/adminlte2/views',
        '@backend/modules' => '@vova07/themes/adminlte2/modules'
    ];


}
