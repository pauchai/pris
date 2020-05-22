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
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Division extends  OwnableItem
{

    public static function tableName()
    {
        return 'divisions';
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
               // Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'company_id' => $migration->integer()->notNull(),
                'division_id' => $migration->tinyInteger(3)->notNull(),
                'title' => $migration->string()->notNull(),
                'order' => $migration->tinyInteger(3),
                'rbac_role' => $migration->string(),

            ],
            'primaries' => [
                [self::class, ['company_id','division_id']]
            ],
            'indexes' => [
                [self::class, ['company_id', 'title'], true],

            ],
            'foreignKeys' => [
                [get_called_class(), 'company_id',Company::class,Company::primaryKey()]
            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }

    public function rules()
    {
        return [
            [['company_id', 'division_id', 'title'],'required'],
           // [['company_id', 'title'],'unique'],
        ];
    }

    public function behaviors()
    {
        return [
            'saveRelations' => [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'ownableitem',
                ],
            ]
        ];
    }

    public static function find()
    {
        return new DivisionQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }



    public  function getDivisionDict()
    {
        return new DivisionDict(['id' => $this->division_id]);

    }

    public function getCompany()
    {
        return $this->hasOne(Company::class, ['__ownableitem_id' => 'company_id']);
    }





}
