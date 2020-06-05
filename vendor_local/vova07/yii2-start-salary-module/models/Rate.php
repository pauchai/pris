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



class Rate extends  Ownableitem
{

    public static function tableName()
    {
        return 'officer_rates';
    }
    public function rules()
    {
        return [

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
                Helper::getRelatedModelIdFieldName(Officer::class) => Schema::TYPE_PK . ' ',
                'amount' => $migration->double('2,2'),

            ],
            'indexes' => [

            ],

           // 'foreignKeys' => [
           //     [get_called_class(), ['officer_id'],Officer::class, Officer::primaryKey()]
           // ],

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

        return $behaviors;
    }

    public static function find()
    {
        return new RateQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }





    public function getOfficer()
    {
        return $this->hasOne(Officer::class,['__person_id'=>'__officer_id']);
    }
    public function getPerson()
    {
        return $this->hasOne(Person::class,['__ident_id'=>'__officer_id']);
    }




}
