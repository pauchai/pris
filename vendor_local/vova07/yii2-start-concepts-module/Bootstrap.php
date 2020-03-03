<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 4:08 PM
 */

namespace vova07\concepts;


use vova07\base\traits\TranslationTrait;
use yii\base\BootstrapInterface;
use yii\i18n\PhpMessageSource;

class Bootstrap implements BootstrapInterface
{
    use TranslationTrait;
    public function bootstrap($app)
    {
        $this->registerTranslations();
    }


}