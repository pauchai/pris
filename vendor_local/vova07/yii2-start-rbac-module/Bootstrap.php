<?php

namespace vova07\rbac;

use vova07\base\traits\TranslationTrait;
use yii\base\BootstrapInterface;

/**
 * Blogs module bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    use TranslationTrait;
    public function bootstrap($app)
    {
        $this->registerTranslations();
    }
}
