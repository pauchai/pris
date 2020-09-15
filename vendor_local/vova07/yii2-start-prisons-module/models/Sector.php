<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\prisons\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\prisons\Module;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Sector extends  OwnableItem
{
    const SECTOR_PU1_S_1_ID = 11411;
    const SECTOR_PU1_S_2_ID = 11412;
    const SECTOR_PU1_S_3_ID = 11413;
    const SECTOR_PU1_S_4_ID = 11414;
    const SECTOR_PU1_D_G_ID = 11415;
    const SECTOR_PU1_B_1_ID = 11416;



    //const SECTOR_SPECIAL_ETAP = 'ETAP';
    //const SECTOR_SPECIAL_RELEASE_91 = 'release 91';
    //const SECTOR_SPECIAL_RELEASE_92 = 'release 92';
   // const SECTOR_SPECIAL_RELEASE_TERM = 'release term';
    use SaveRelationsTrait;

    public static function tableName()
    {
        return 'sector';
    }
    public function rules()
    {
        return [
            [['title'],'required']
        ];
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
                'prison_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            ],
            'indexes' => [
                [self::class, ['title'], true],
                [self::class, ['prison_id']],
            ],
           // 'dependsOn' => [
           //     Prison::class
           //],
            'foreignKeys' => [
                [get_called_class(), 'prison_id',Prison::class,Prison::primaryKey()]
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
                        'prison',

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
        return new SectorQuery(get_called_class());
    }
    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }

    public function getPrison()
    {
        return $this->hasOne(Prison::class,['__company_id' => 'prison_id']);
    }

    public static function getListForCombo($prison_id = null)
    {
        if ($prison_id){
            $filter = ['prison_id'=>$prison_id];
        } else {
            $filter  = [];
        }
        return ArrayHelper::map(self::find()->andFilterWhere($filter)->all(),'__ownableitem_id','title');
    }
    public function getCells()
    {
        return $this->hasMany(Cell::class,['sector_id'=>'__ownableitem_id']);
    }
    public function getCellsForCombo()
    {
        return ArrayHelper::map($this->cells,'__ownableitem_id','number');
    }

    public function attributeLabels()
    {
        return [
          'title' => Module::t('labels','SECTOR_TITLE_LABEL')
        ];
    }


}

