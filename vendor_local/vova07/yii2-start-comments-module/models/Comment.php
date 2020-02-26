<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\comments\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\psycho\Module;
use vova07\prisons\models\Prison;
use vova07\tasks\models\CommitteeQuery;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;

/**
 * @property integer $risk_id
 * @property boolean $feature_violent
 * @property boolean $feature_self_torture
 * @property boolean $feature_sucide
 * @property boolean $feature_addiction_alcohol
 * @property boolean $feature_addiction_drug

 */

class Comment extends  Ownableitem
{

    public static function tableName()
    {
        return 'comments';
    }

    public function rules()
    {
        return [
            [['item_id','content'],'required'],
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
