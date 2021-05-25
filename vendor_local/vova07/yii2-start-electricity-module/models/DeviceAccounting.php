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


class DeviceAccounting extends  Ownableitem
{
    const HOURS_PER_WORKING_DAYS = 6;
    const HOURS_PER_HOLIDAYS_AND_WEEKEND = 8;
    const HOURS_PER_DAY_FOR_TEE_POT = 2;
    const HOURS_PER_DAY_FOR_FREEDGE = 6;
    const HOURS_PER_DAY_1 = 1;
    const HOURS_PER_DAY_0_5 = 0.5;

    const STATUS_INIT = 1;
    const STATUS_READY_FOR_PROCESSING = 2;
    const STATUS_PROCESSED = 3;

    public $skip_auto_calculation = false;

    // public $fromDateJui;
    // public $toDateJui;

    public static function tableName()
    {
        return 'device_accountings';
    }

    public function rules()
    {
        return [
            [['device_id', 'dateRange'], 'required'],
            //   [['from_date'],'default', 'value' => Calendar::getRangeForDate(time())[0]->getTimeStamp()],
            //   [['to_date'],'default', 'value' => Calendar::getRangeForDate(time())[1]->getTimeStamp()],
            [['value','prisoner_id','price'], 'number'],
           // [['fromDateJui','toDateJui'],'string'],
            [['status_id'], 'default', 'value' => DeviceAccounting::STATUS_INIT],
            [['skip_auto_calculation'], 'boolean'],

            //DefaultValueValidator::


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
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'prisoner_id' => $migration->integer()->notNull(),
                'device_id' => $migration->integer()->notNull(),
                'from_date' => $migration->bigInteger()->notNull(),
                'to_date' => $migration->bigInteger()->notNull(),
                'value' => $migration->double('4,2')->notNull(),
                'price' => $migration->double('4,2'),
                'status_id' => $migration->tinyInteger()->notNull(),
               // 'balance_id' => $migration->integer(),

            ],
            'indexes' => [
                [self::class, 'prisoner_id'],
                [self::class, 'device_id'],
                [self::class, 'from_date'],
                [self::class, 'to_date'],
                [self::class, 'status_id'],

            ],
            'foreignKeys' => [
                [self::class, 'prisoner_id', Prisoner::class, Prisoner::primaryKey()],
                [self::class, 'device_id', Device::class, Device::primaryKey()],
                //[self::class, 'balance_id', Balance::class, Balance::primaryKey()],
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }

    public function behaviors()
    {

        if (get_called_class() == self::class) {
            $behaviors = [

                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'ownableitem',
                    ],
                ],
                'autoCalculation' => [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                        \yii\db\ActiveRecord::EVENT_AFTER_VALIDATE => 'value',
                        //\yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => 'value',
                    ],
                    'value' => function ($event) {
                        $value = $event->sender->autoCalculation();
                        if (!$event->sender->skip_auto_calculation)
                            $event->sender->price = $event->sender->calculatePrice();
                        return $value;

                    },
                ],
               /* 'savePrice' => [
                    'class' => AttributeBehavior::class,
                    'attributes' => [
                         \yii\db\ActiveRecord::EVENT_AFTER_VALIDATE => 'price',
                         //\yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'price',
                    ],
                    'value' => function ($event) {
                       // if (!$this->skipAutocalculation)
                         return $event->sender->calculatePrice();

                    },
                ],*/

            ];
        } else {
            $behaviors = [];
        }
        $behaviors['dateRange'] = [
            'class' => \vova07\base\components\DateRangeBehavior::class,
            'attribute' => 'dateRange',
            'dateStartAttribute' => 'from_date',
            'dateEndAttribute' => 'to_date',
            'displayFormat' => 'd-m-Y'


        ];/*
                $behaviors['fromDateJui']      = [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'from_date',
                'juiAttribute' => 'fromDateJui'
                ];
                $behaviors['toDateJui'] = [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'to_date',
                'juiAttribute' => 'toDateJui'
                ];*/



        return $behaviors;
    }

    public static function find()
    {
        return new DeviceAccountingQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }


    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), '__ownableitem_id', 'title');
    }


    public function getDevice()
    {
        return $this->hasOne(Device::class, ['__ownableitem_id' => 'device_id']);
    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class, ['__person_id' => 'prisoner_id']);
    }


    public function getBalances()
    {
        return $this->hasMany(Balance::class, ['__ownableitem_id' => 'balance_id'])
            ->via('deviceAccountingBalances');
    }
    public function getUnPaid()
    {
        return $this->price - $this->getBalances()->sum('amount');
    }
    public function getDeviceAccountingBalances()
    {
        return $this->hasMany(DeviceAccountingBalance::class, ['device_accounting_id' => '__ownableitem_id']);
    }

/*    public function getBalance()
    {
        return $this->hasOne(Balance::class, ['__ownableitem_id' => 'balance_id']);
    }*/

    public static function getStatusesForCombo($key = null)
    {

        $ret = [
            self::STATUS_INIT => Module::t('default', 'STATUS_INIT_LABEL'),
            self::STATUS_READY_FOR_PROCESSING => Module::t('default', 'STATUS_READY_FOR_PROCESSING'),
            self::STATUS_PROCESSED => Module::t('default', 'STATUS_PROCESSED'),
        ];
        if ($key) {
            return $ret[$key];
        } else {
            return $ret;
        }

    }

    public function getStatus()
    {
        return self::getStatusesForCombo($this->status_id);
    }

    public function autoCalculation()
    {
        if (!($this->value) && $this->device->enable_auto_calculation) {

            if ($this->device->assigned_at > $this->from_date)
                $fromDate = (new \DateTime())->setTimestamp($this->device->assigned_at);
            else
                $fromDate = (new \DateTime())->setTimestamp($this->from_date);

            $toDate = (new \DateTime())->setTimestamp($this->to_date);
            $dateDiff = date_diff($fromDate, $toDate);
            $value = 0;
            while ($fromDate <= $toDate) {
                switch ($this->device->calculation_method_id) {
                    case Device::CALCULATION_METHOD_TEE_POT:
                        $value = $value + self::autoCalculate_TeaPot($this, $fromDate);
                        break;
                    case Device::CALCULATION_METHOD_FREEDGE:
                        $value = $value + self::autoCalculate_Freedge($this, $fromDate);
                        break;
                    case Device::CALCULATION_METHOD_HOURS1:
                        $value = $value + self::autoCalculate_Hours1($this, $fromDate);
                        break;
                    case Device::CALCULATION_METHOD_HOURS0_5:
                        $value = $value + self::autoCalculate_Hours0_5($this, $fromDate);
                        break;
                    default:
                        $value = $value + self::autoCalculate_6AND8($this, $fromDate);

                }

                $fromDate->modify("+1 day");
            }
            return $value;
        } else {
            if (is_null($this->value))
                return 0;
            else
                return $this->value;
        }
    }

    public function calculatePrice()
    {
        return $this->value * \vova07\site\models\Setting::getValue(\vova07\site\models\Setting::SETTING_FIELD_ELECTRICITY_KILO_WATT_PRICE);
    }

    public static function autoCalculate_6AND8($model,$dateTime)
    {
        if (Calendar::isHoliday($dateTime->format('Y-m-d'))){
            return self::HOURS_PER_HOLIDAYS_AND_WEEKEND * $model->device->power/1000;
        } else {
            return self::HOURS_PER_WORKING_DAYS  * $model->device->power/1000;
        }
    }
    public static function autoCalculate_TeaPot($model,$dateTime)
    {
            return self::HOURS_PER_DAY_FOR_TEE_POT * $model->device->power/1000;
    }
    public static function autoCalculate_Freedge($model, $dateTime)
    {
        return self::HOURS_PER_DAY_FOR_FREEDGE * $model->device->power/1000;
    }

    public static function autoCalculate_Hours1($model,$dateTime)
    {
        return self::HOURS_PER_DAY_1 * $model->device->power/1000;
    }
    public static function autoCalculate_Hours0_5($model,$dateTime)
    {
        return self::HOURS_PER_DAY_0_5 * $model->device->power/1000;
    }

    public function attributeLabels()
    {
        return [
          'dateRange' => Module::t('labels','DATE_RANGE'),
            'value' => Module::t('labels','DEVICE_ACCOUNTING_VALUE'),
            'status' => Module::t('labels','STATUS_LABEL'),
            'status_id' => Module::t('labels','STATUS_LABEL'),


        ];
    }


}

