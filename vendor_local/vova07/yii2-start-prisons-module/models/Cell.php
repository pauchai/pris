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
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class Cell extends  OwnableItem
{

    const PRISONERS_PER_SQUARE_3 = 3;
    const PRISONERS_PER_SQUARE_4 = 4;

    use SaveRelationsTrait;

    public static function tableName()
    {
        return 'cells';
    }
    public function rules()
    {
        return [
            [['number'],'required'],
            [['square'],'number'],
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
                'number' => Schema::TYPE_STRING . ' NOT NULL',
                'sector_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'square' => Schema::TYPE_DOUBLE,
            ],
            'indexes' => [
                [self::class, ['sector_id','number'], true],
                [self::class, ['sector_id']],
            ],
           // 'dependsOn' => [
           //     Prison::class
           //],
            'foreignKeys' => [
                [get_called_class(), 'sector_id',Sector::class,Sector::primaryKey()]
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

    public function getSector()
    {
        return $this->hasOne(Sector::class,['__ownableitem_id' => 'sector_id']);
    }
    public function getPrisoners()
    {
        return $this->hasMany(Prisoner::class, ['cell_id' => '__ownableitem_id']);
    }
    public static function getListForCombo($sector_id = null)
    {
        if ($sector_id){
            $filter = ['sector_id'=>$sector_id];
        } else {
            $filter  = [];
        }

        return ArrayHelper::map(self::find()->andFilterWhere($filter)->all(),'__ownableitem_id','number');
    }

    public function attributeLabels()
    {
        return [
            'number' => Module::t('labels','CELL_NUMBER_LABEL')
        ];
    }
    public function getEstimatePrisonersCount($estimatePrisonersPerSquare = self::PRISONERS_PER_SQUARE_4)
    {
        if (is_null($this->square ))
            return null;
        return $this->square / $estimatePrisonersPerSquare;
    }

}
