<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/24/19
 * Time: 10:05 AM
 */

namespace vova07\base\components;


use yii\i18n\MissingTranslationEvent;

class TranslationEventHandler
{
    public static function handleMissingTranslation(MissingTranslationEvent $event) {
        $event->translatedMessage = "@MISSING: {$event->category}.{$event->message} FOR LANGUAGE {$event->language} @";
    }
}