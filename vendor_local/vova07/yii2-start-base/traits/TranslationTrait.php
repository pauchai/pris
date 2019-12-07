<?php

namespace vova07\base\traits;

use Yii;
use yii\i18n\DbMessageSource;
use yii\i18n\PhpMessageSource;

/**
 * Class ModuleTrait
 * @package vova07\users\traits
 * Implements `getModule` method, to receive current module instance.
 */
trait TranslationTrait
{
    public function registerTranslations()
    {
        //\Yii::$app->i18n->translations['vova07/modules/' . self::getModuleName() . '/*'] = [
            //'class' => PhpMessageSource::class,
            //'class' => DbMessageSource::class,
            //'basePath' => '@vova07/' . self::getModuleName() . '/messages',
            //'forceTranslation' => true,
            //'fileMap' => [
            //    'modules/' . self::getModuleName() . '/default' => 'default.php',
            //]
       // ];
    }

    public static function getModuleName()
    {
        return self::getClassInfoArray()[1];
    }
    public static function getAuthorName()
    {
        return self::getClassInfoArray()[0];
    }
    private static function getClassInfoArray()
    {
        static $arr;
        $key = get_called_class();
        if (!isset($arr[$key])){
            $arr[$key] = preg_split('#\\\#',get_called_class());
        }
        return $arr[$key];

    }
}
