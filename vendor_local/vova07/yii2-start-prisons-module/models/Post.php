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


class Post extends  OwnableItem
{
    public static function tableName()
    {
        return 'posts';
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                //Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'company_id' => $migration->integer()->notNull(),
                'division_id' => $migration->tinyInteger(3)->notNull(),
                'postdict_id' => $migration->smallInteger()->notNull(),
                'title' => $migration->string()->notNull(),
                'rates' => $migration->double('2,1')->comment('number of rates (ex: 1, 0.5, 2.5 etc)'),
                 'order' => $migration->smallInteger(),
                'rbac_role' => $migration->string(),

            ],
            'primaries' => [
                [self::class, ['company_id','division_id', 'postdict_id']]
            ],

            'foreignKeys' => [
                [get_called_class(), 'company_id',Company::class,Company::primaryKey()],
                [get_called_class(), ['company_id','division_id'],Division::class,Division::primaryKey()],
                [get_called_class(), 'postdict_id',PostDict::class,PostDict::primaryKey()],

            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }

    public function rules()
    {
        return [
            [['company_id', 'division_id', 'postdict_id', 'title'],'required'],
            [['rates'],'number']
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
        return new PostQuery(get_called_class());
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }



    public  function getPostDict()
    {
        return $this->hasOne(PostDict::class,['id' => 'postdict_id']);

    }

    public function getCompany()
    {
        return $this->hasOne(Company::class, ['__ownableitem_id' => 'company_id']);
    }
    public function getDivision()
    {
        return $this->hasOne(Division::class, [
            'company_id' => 'company_id',
            'division_id' => 'division_id',
            ]);
    }
    public function getOfficerPosts()
    {
        return $this->hasMany(OfficerPost::class, [
            'company_id' => 'company_id',
            'division_id' => 'division_id',
            'postdict_id' => 'postdict_id',
        ]);
    }






}
