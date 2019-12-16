<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\prisons\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Department extends  OwnableItem
{


    const SPECIAL_SOCIAL_REINTEGRATION = 'social reintegration';
    const SPECIAL_FINANCE = 'finance';
    const SPECIAL_LOGISTIC = 'logistic';
    const SPECIAL_ADMINISTRATION = 'administration';

    public static function tableName()
    {
        return 'department';
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
//                'company_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            ],
            'indexes' => [
                [self::class, ['title'], true],
//                [self::class, ['company_id']],

            ],
            'foreignKeys' => [
  //              [get_called_class(), 'company_id',Company::class,Company::primaryKey()]
            ],
            //'dependsOn' => [
            //    Company::class
            //]


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }

    public function rules()
    {
        return [
            [['title'],'required'],
            [['title'],'unique'],
        ];
    }

    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'ownableitem',
                    'departments'
                ],
            ]
        ];
    }

    public static function find()
    {
        return new DepartmentQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(),'__ownableitem_id','title');

    }



}
