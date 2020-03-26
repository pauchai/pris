<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/1/19
 * Time: 5:47 PM
 */

namespace vova07\base\models;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\users\models\Ident;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class Ownableitem extends Item
{
    public static function tableName()
    {
        return "ownableitem";
    }

    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(Item::class) => Schema::TYPE_PK . ' ',
                'created_by' => Schema::TYPE_INTEGER . " NOT NULL",
                'updated_by' => Schema::TYPE_INTEGER . " NOT NULL",
            ],
            'foreignKeys' => [
                [get_class(), 'created_by',Ident::class,'__item_id'],
                [get_class(), 'updated_by',Ident::class,'__item_id'],
            ]
        ];
        return $metadata;

    }

    public function rules()
    {
        return [
            [['__item_id'], 'required'],
            [['item'], 'safe']
        ];
    }

    public function behaviors()
    {
        if (get_called_class() == self::class) {
            $behaviors = [
                [
                    'class' => BlameableBehavior::class,

                ],
                'saveRelations' =>[
                    'class'     => SaveRelationsBehavior::class,
                    'relations' => [
                        'item',
                    ],
                ]
            ];
        } else {
            $behaviors = [];
        }

        return ArrayHelper::merge(parent::behaviors(), $behaviors);
    }
    public function getItem()
    {
       return $this->hasOne(Item::class,['id' => '__item_id']);
    }

    public function getCreatedBy()
    {
        return $this->hasOne(Ident::class,['__item_id' => 'created_by']);
    }


   // abstract public function getOwnableitem();







}