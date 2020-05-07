<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\electricity\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\electricity\Module;
use vova07\prisons\models\Cell;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use vova07\users\models\PrisonerQuery;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Device extends  Ownableitem
{
    const CALCULATION_METHOD_6_AND_8 = 1;
    const CALCULATION_METHOD_FREEDGE = 2;
    const CALCULATION_METHOD_TEE_POT = 3;
    const CALCULATION_METHOD_HOURS1 = 4;
    const CALCULATION_METHOD_HOURS0_5 = 5;

    const STATUS_ID_ACTIVE = 1;


    public static function tableName()
    {
        return 'devices';
    }

    public function rules()
    {
        return [
            [[ 'title', 'power','enable_auto_calculation', 'status_id'], 'required'],
            [['prisoner_id','sector_id','calculation_method_id'],'safe'],
            [['assignedAtJui','unassignedAtJui'],'date'],
            [['cell_id'],'integer'],
            [['status_id'], 'default', 'value' => Device::STATUS_ID_ACTIVE]

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
                'title' => $migration->string()->notNull(),
                'calculation_method_id' => $migration->tinyInteger(),
                'prisoner_id' => $migration->integer() ,
                'assigned_at' => $migration->bigInteger(),
                'unassigned_at' => $migration->bigInteger(),
                'sector_id' => $migration->integer(),
                'cell_id' => $migration->integer(),
                'power' => $migration->integer()->notNull(),
                'enable_auto_calculation' => $migration->boolean()->notNull()->defaultValue(true),
                'status_id' => $migration->tinyInteger()->notNull(),


            ],
            'indexes' => [
                [self::class, 'prisoner_id'],
                [self::class, 'title'],
                [self::class, 'sector_id'],
                [self::class, 'cell_id'],
                [self::class, 'assigned_at'],
                [self::class, 'unassigned_at'],
                [self::class, ['title','prisoner_id','power','sector_id','cell_id'],'unique'],
                [self::class, 'status_id'],
            ],
            'foreignKeys' => [
                [self::class, 'prisoner_id', Prisoner::class, Prisoner::primaryKey()],
                [self::class, 'sector_id', Sector::class, Sector::primaryKey()],
                [self::class, 'cell_id', Cell::class, Cell::primaryKey()],

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
                'assignedAtJui' =>  [
                    'class' => DateJuiBehavior::class,
                    'attribute' => 'assigned_at',
                    'juiAttribute' => 'assignedAtJui',
                ],

                'unAssignedAtJui' =>  [
                    'class' => DateJuiBehavior::class,
                    'attribute' => 'unassigned_at',
                    'juiAttribute' => 'unassignedAtJui',
                ],


            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new DeviceQuery(self::class);
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }


    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), '__ownableitem_id', 'title');
    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id'=>'prisoner_id']);
    }
    public function getDeviceAccountings()
    {
        return $this->hasMany(DeviceAccounting::class, ['device_id' => '__ownableitem_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id'=>'prisoner_id']);
    }
    public function getSector()
    {
        return $this->hasOne(Sector::class,['__ownableitem_id'=>'sector_id']);
    }
    public function getCell()
    {
        return $this->hasOne(Cell::class,['__ownableitem_id'=>'cell_id']);
    }

    public static function getCalculationMethodsListForCombo($key = null)
    {
        $ret =  [
          self::CALCULATION_METHOD_6_AND_8 => Module::t('default','CALCULATION_METHOD_6_AND_8'),
            self::CALCULATION_METHOD_TEE_POT => Module::t('default','CALCULATION_METHOD_TEE_POT'),
            self::CALCULATION_METHOD_FREEDGE => Module::t('default','CALCULATION_METHOD_FREEDGE'),
             self::CALCULATION_METHOD_HOURS1 => Module::t('default','CALCULATION_METHOD_HOURS1'),
            self::CALCULATION_METHOD_HOURS0_5 => Module::t('default','CALCULATION_METHOD_HOURS0_5')
        ];
        if (!is_null($key) ) {
            if (isset($ret[$key]))
                return $ret[$key];
            else
                throw new \LogicException('Key Doesnt exists');
        } else
            return $ret;
    }
    public function getCalculationMethod()
    {
        if (is_null($this->calculation_method_id))
            return null;
        else
            return self::getCalculationMethodsListForCombo($this->calculation_method_id);
    }

    public function attributeLabels()
    {
        return [
            'title' => Module::t('labels','DEVICE_TITLE_LABEL'),
            'prisoner_id' => Module::t('labels','DEVICE_PRISONER_ID_LABEL'),
            'assigned_at' => Module::t('labels','ASSIGNED_AT_LABEL'),
            'assignedAtJui' => Module::t('labels','ASSIGNED_AT_LABEL'),
            'unassignedAtJui' => Module::t('labels','UNASSIGNED_AT_LABEL'),
            'unassigned_at' => Module::t('labels','UNASSIGNED_AT_LABEL'),

            'power' => Module::t('labels','DEVICE_POWER_LABEL'),
            'enable_auto_calculation' => Module::t('labels','ENABLE_AUTO_CALCULATION'),
            'calculationMethod' => Module::t('labels','CALCULATION_METHOD_TITLE'),
            'calculation_method_id' => Module::t('labels','CALCULATION_METHOD_TITLE'),
            'status_id' => Module::t('labels','DEVICE_STATUS_TITLE'),
            'status' => Module::t('labels','DEVICE_STATUS_TITLE'),

        ];
    }
    public static function getStatusesForCombo()
    {
        return [
            self::STATUS_ID_ACTIVE => Module::t('default', 'STATUS_ACTIVE_LABEL'),
            self::STATUS_ID_DELETED => Module::t('default', 'STATUS_DELETED_LABEL')
        ];
    }
    public function getStatus()
    {
        if ($this->status_id)
            return self::getStatusesForCombo()[$this->status_id];
        else
            return null;
    }

}

