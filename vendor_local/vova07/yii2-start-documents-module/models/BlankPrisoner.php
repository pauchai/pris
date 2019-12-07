<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\documents\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\prisons\Module;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class BlankPrisoner extends  Ownableitem
{

    public static function tableName()
    {
        return 'blank_prisoner';
    }
    public function rules()
    {
        return [
            [['blank_id','prisoner_id', 'content'], 'required'],

        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
               // Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'content' => Schema::TYPE_TEXT . ' NOT NULL',
                'blank_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL'



            ],
            'indexes' => [
            ],
            'primaries' => [
                [self::class, ['blank_id','prisoner_id']]
            ],

            'foreignKeys' => [
                [get_called_class(), 'blank_id',Blank::class,Blank::primaryKey()],
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
                        'blank',
                        'prisoner'


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
        return new BlankPrisonerQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }
    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id' => 'prisoner_id']);
    }
    public function getBlank()
    {
        return $this->hasOne(Blank::class,['id' => 'blank_id']);
    }


    /*
    public function beforeValidate()
    {
        return $this->beforeSaveActiveRecordMetaModel(true);
    }*/



    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(),'__ownableitem_id','title');
    }





}
