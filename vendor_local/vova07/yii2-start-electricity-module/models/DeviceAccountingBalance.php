<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\electricity\models;



use Codeception\Lib\Interfaces\ActiveRecord;
use kartik\daterange\DateRangeBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\finances\models\Balance;
use vova07\jobs\helpers\Calendar;
use vova07\electricity\Module;
use vova07\prisons\models\Company;
use vova07\prisons\models\Prison;
use vova07\site\models\Setting;
use vova07\users\models\Officer;
use vova07\users\models\Prisoner;
use yii\base\Behavior;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use vova07\tasks\models\CommitteeQuery;
use yii\validators\DateValidator;
use yii\validators\DefaultValueValidator;


class DeviceAccountingBalance extends  ActiveRecordMetaModel
{


    public static function tableName()
    {
        return 'device_accounting_balance';
    }

    public function rules()
    {
        return [
            [['device_accounting_id', 'balance_id'], 'required'],


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
                'device_accounting_id' => $migration->integer()->notNull(),
                'balance_id' => $migration->integer()->notNull(),

            ],
            'primaries' => [
                [self::class,['device_accounting_id','balance_id']]
            ],
            'foreignKeys' => [
                [self::class, 'device_accounting_id',DeviceAccounting::class,DeviceAccounting::primaryKey()],
                [self::class, 'balance_id',Balance::class,Balance::primaryKey()],
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }



    public static function find()
    {
        return new DeviceAccountingBalanceQuery(get_called_class());
    }



    public function getDevice()
    {
        return $this->hasOne(Device::class, ['__ownableitem_id' => 'device_id']);
    }



    public function getBalance()
    {
        return $this->hasOne(Balance::class, ['__ownableitem_id' => 'balance_id']);
    }




}

