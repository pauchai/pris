<?php

namespace vova07\themes\site_default;

use Yii;

/**
 * Class Theme
 * @package vova07\themes\site_default
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@frontend/views' => '@vova07/themes/site_default/views',
        '@frontend/modules' => '@vova07/themes/site_default/modules'
    ];

}
