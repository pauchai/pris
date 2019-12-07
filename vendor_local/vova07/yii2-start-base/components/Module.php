<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 10:44 AM
 */

namespace vova07\base\components;


use vova07\base\traits\TranslationTrait;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\i18n\PhpMessageSource;

abstract class Module extends \yii\base\Module
{
    use TranslationTrait;

    const APPLICATION_FRONTEND = 'frontend';
    const APPLICATION_BACKEND = 'backend';
    const APPLICATION_API = 'api';

    public $appContext = self::APPLICATION_FRONTEND;

    public static function t($category, $message, $params = [], $language = null)
    {
        if (is_null($category) || $category==''){
            $category = 'default';
        }
        return \Yii::t('vova07/modules/' . self::getModuleName() . '/' . $category, $message, $params, $language);
    }



    public function init()
    {


        if (in_array($this->appContext, self::getAppContextsSupported() )) {
            $this->setViewPath('@' . static::getAuthorName() . '/' . static::getModuleName() . '/views/' . $this->appContext);
            if ($this->controllerNamespace === null) {
                $this->controllerNamespace = static::getAuthorName() . '\\' . static::getModuleName() . '\\' .'controllers' . '\\' . $this->appContext;
            }
        } else {
            throw new InvalidConfigException("Context " . $this->appContext . 'Doesnt supported' );
        }


        parent::init();
    }

    public static function getAppContextsSupported()
    {
        return [
            self::APPLICATION_FRONTEND,
            self::APPLICATION_BACKEND,
            self::APPLICATION_API
        ];

    }






}