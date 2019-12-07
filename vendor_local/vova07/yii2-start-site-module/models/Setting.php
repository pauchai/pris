<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\site\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\plans\Module;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\console\controllers\MigrateController;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use vova07\tasks\models\CommitteeQuery;
use yii\validators\DateValidator;


class Setting extends  ActiveRecordMetaModel
{
    const SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE = 'kilo_watt_price';
    const SETTING_FIELD_COMPANY_DIRECTOR = 'director';

    public static function tableName()
    {
        return 'settings';
    }

    public function rules()
    {
        return [
            [['prison_id'], 'required'],
            [[self::SETTING_FIELD_COMPANY_DIRECTOR, self::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE], 'safe']
        ];


    }

    /**
     *
     */
    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                'prison_id' => $migration->integer(),
                 self::SETTING_FIELD_COMPANY_DIRECTOR => $migration->integer(),
                 self::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE => $migration->double('2,2'),
            ],
            'indexes' => [
                [self::class, 'prison_id'],

            ],
            'foreignKeys' => [
                [self::class, self::SETTING_FIELD_COMPANY_DIRECTOR, Person::class, Person::primaryKey()],

            ],
            'primaries' => [
                [self::class, ['prison_id']]
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }


    public static function find()
    {
        return new SettingQuery(get_called_class());
    }


    public function attributeLabels()
    {
        return [
            self::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE => \vova07\site\Module::t('settings','SETTING_ELECTRICITY_KILO_WATT_PRICE'),
            self::SETTING_FIELD_COMPANY_DIRECTOR => \vova07\site\Module::t('settings','SETTING_COMPANY_DIRECTOR')
        ];
    }



    public function getPrison()
    {
        return $this->hasOne(Prison::class,['__company_id'=>'prison_id']);
    }
    public function getDirectorOfficer()
    {
        return $this->hasOne(Officer::class,['__person_id'=>'director']);
    }

    public static function getValue($settingName,$prison_id=null)
    {
       return self::getInstance($settingName,$prison_id);
    }
    public static function getInstance($settingName=null, $prison_id=null)
    {
        static $settingItem;

        if (is_null($prison_id)){
            $prison = Company::findOne(['alias'=>Company::PRISON_DEPARTMENT]);
            $prison_id = $prison->primaryKey;
        }

        if (is_null($settingItem)){
            $settingItem = Setting::findOne($prison_id);

        }
        if ($settingName)
            return $settingItem->$settingName;
        else
            return $settingItem;

    }






}

