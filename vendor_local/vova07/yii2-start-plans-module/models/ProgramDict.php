<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class ProgramDict extends  Ownableitem
{

    public static function tableName()
    {
        return 'program_dicts';
    }
    public function rules()
    {
        return [
            [['title','slug', 'group_id'], 'required'],

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
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'slug' => Schema::TYPE_STRING . " NOT NULL",
                'group_id' => $migration->tinyInteger()->notNull(),
                'origin_id' => Schema::TYPE_INTEGER
            ],

            'foreignKeys' => [
                [self::class, 'group_id',PlanItemGroup::class,PlanItemGroup::primaryKey()],



            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );

    }

    public function behaviors()
    {

        if (get_called_class() == self::class) {
            $behaviors = [
                [
                    'class' => SluggableBehavior::class,
                    'attribute' => 'title',
                    'slugAttribute' => 'slug',

                    'ensureUnique' => true,
                ],
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
        return new ProgramDictQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }

    public function getGroup()
    {
        return $this->hasOne(PlanItemGroup::class,['id' => 'group_id']);
    }


    public static function getListForCombo($group_id = null)
    {
        $query = self::find();
        if ($group_id)
            $query->andWhere(['group_id' => $group_id]);

        return ArrayHelper::map($query->asArray()->all(),'__ownableitem_id','title');
    }

    public static function getListForComboByUserGroup()
    {
        $planGroup = \Yii::$app->user->identity->planGroup;
        $groupId = ArrayHelper::getValue($planGroup,'id');
        return self::getListForCombo($groupId);
    }

    public function getPlanGroup()
    {
        return $this->hasOne(PlanItemGroup::class, ['id' => 'group_id']);
    }



}
