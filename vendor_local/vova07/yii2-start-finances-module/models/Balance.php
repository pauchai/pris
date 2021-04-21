<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\finances\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\finances\Module;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;



class Balance extends  Ownableitem
{
    const TYPE_DEBIT = 1;
    const TYPE_CREDIT = 2;
    public static function tableName()
    {
        return 'balances';
    }
    public function rules()
    {
        return [
            [['type_id','category_id','prisoner_id','amount','atJui'], 'required'],
            [['amount'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],
            [['reason'],'string']
        ];
    }
    public function beforeSave($insert) {

        if (parent::beforeSave($insert)) {

            $this->amount = str_replace(",", ".", $this->amount);

            return true;

        } else {

            return false;

        }

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
                'type_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'amount' => $migration->decimal(10,2),
                'reason' => Schema::TYPE_STRING,
                'at' => Schema::TYPE_DATE,
            ],
            'indexes' => [

            ],

            'foreignKeys' => [
                [get_called_class(), ['type_id','category_id'],BalanceCategory::class,BalanceCategory::primaryKey()],
                [get_called_class(), ['prisoner_id'],Prisoner::class, Prisoner::primaryKey()]
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );

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
                ]

            ];
        } else {
            $behaviors = [];
        }
        $behaviors = ArrayHelper::merge($behaviors,[
            'atJui' => [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'at',
                'juiAttribute' => 'atJui'
            ]
        ]);
        return $behaviors;
    }

    public static function find()
    {
        return new BalanceQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }


    public static function getTypesForCombo($key=null)
    {
        $ret = [
          self::TYPE_DEBIT => Module::t('default','DEBIT_TITLE'),
          self::TYPE_CREDIT => Module::t('default','CREDIT_TITLE'),
        ];
        if ($key)
            return $ret[$key];
        else
            return $ret;

    }

    public function getTypeTitle()
    {
        return self::getTypesForCombo($this->type_id);
    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id'=>'prisoner_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ownableitem_id'=>'prisoner_id']);
    }
    public function getCategory()
    {
        return $this->hasOne(BalanceCategory::class,['category_id'=>'category_id' , 'type_id'=>'type_id']);
    }

    public function attributeLabels()
    {
        return [
            'atJui' => Module::t('labels','BALANCE_AT_VALUE'),
            'prisoner_id' => Module::t('labels','BALANCE_PRISONER_VALUE'),
            'type_id' => Module::t('labels','BALANCE_TYPE_VALUE'),
            'category_id' => Module::t('labels','BALANCE_CATEGORY_VALUE'),
            'amount' => Module::t('labels','BALANCE_AMOUNT_VALUE'),
            'reason' => Module::t('labels','BALANCE_REASON_VALUE'),


        ];
    }


}
