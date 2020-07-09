<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/1/19
 * Time: 5:52 PM
 */

namespace vova07\countries\models;


use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\countries\Module;
use yii\db\ActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
 * Class Ident
 * @package vova07\users\models
 */
class Country extends ActiveRecord
{
    const ISO_MOLDOVA = 'MD';

    public function rules()
    {
        return [
            [['title', 'iso'], 'required'],
            [['title', 'iso'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('default', 'MODEL_ID'),
            'title' => Module::t('default', 'COUNTRY_TITLE'),
            'iso' => Module::t('default', 'COUNTRY_ISO'),
        ];
    }
    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->orderBy('title')->asArray()->all(), 'id', 'title');
    }

}