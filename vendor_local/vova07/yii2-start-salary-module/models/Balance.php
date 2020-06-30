<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\salary\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\salary\Module;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;



class Balance extends  Ownableitem
{

    public static function tableName()
    {
        return 'officer_balances';
    }
    public function rules()
    {
        return [
            [['category_id','officer_id','amount','atJui'], 'required'],
            [['amount'], 'number', 'numberPattern' => '/^\s*[-+]?[0-9]*[.,]?[0-9]+([eE][-+]?[0-9]+)?\s*$/'],

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
                'category_id' => $migration->tinyInteger(3)->notNull(),
                'officer_id' => $migration->integer()->notNull(),
                'amount' => $migration->double('2,2'),
                'reason' => $migration->string(),
                'at' => Schema::TYPE_DATE,
            ],
            'indexes' => [

            ],

            'foreignKeys' => [
                [get_called_class(), ['officer_id'],Officer::class, Officer::primaryKey()]
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





    public function getOfficer()
    {
        return $this->hasOne(Officer::class,['__person_id'=>'officer_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id'=>'prisoner_id']);
    }
    public function getCategory()
    {
        return new BalanceCategory(['id' => $this->category_id]);
    }

    public function attributeLabels()
    {
        return [
            'atJui' => Module::t('labels','BALANCE_AT_VALUE'),
            'officer_id' => Module::t('labels','BALANCE_OFFICER_VALUE'),
            'category_id' => Module::t('labels','BALANCE_CATEGORY_VALUE'),
            'amount' => Module::t('labels','BALANCE_AMOUNT_VALUE'),



        ];
    }


}
