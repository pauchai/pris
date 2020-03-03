<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\concepts\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;




class Concept extends  Ownableitem
{

    public static function tableName()
    {
        return 'concepts';
    }

    public function rules()
    {
        return [
            //[['item_id','content'],'required'],
        ];

    }


    public static function getMetadata()
    {

        $migration = new Migration();
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'item_id' => $migration->integer()->notNull(),
                'content' => $migration->text()->notNull(),
            ],


            'foreignKeys' => [
                [self::class, 'item_id', Item::class, Item::primaryKey()],

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
            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new CommentQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }


    public function getItem()
    {
        return $this->hasOne(Item::class, ['id'=>'item_id']);
    }






}
