<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\users\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\prisons\models\Cell;
use vova07\prisons\models\Prison;
use vova07\prisons\models\Sector;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class PrisonerLocationJournal extends  ActiveRecordMetaModel
{



    public static function tableName()
    {
        return 'location_journal';
    }
    public function rules()
    {
        return [
            [['prisoner_id','prison_id'],'required']
        ];
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $migration  = new Migration();
        $metadata = [
            'fields' => [
                //Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'id' => $migration->primaryKey(),
                'prisoner_id' => $migration->integer()->notNull(),
                'prison_id' => $migration->integer()->notNull(),
                'sector_id' => $migration->integer(),
                'cell_id' => $migration->integer(),
                'at' => $migration->integer(),
            ],
            'indexes' => [
                [self::class, ['prisoner_id']],
                [self::class, ['prison_id']],
                [self::class, ['sector_id']],
                [self::class, ['cell_id']],
                [self::class, ['at']],

            ],
           // 'dependsOn' => [
           //     Prison::class
           //],
            'foreignKeys' => [
                [get_called_class(), 'prisoner_id',Prisoner::class,Prisoner::primaryKey()],
                [get_called_class(), 'prison_id',Prison::class,Prison::primaryKey()]
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }

    public function behaviors()
    {
        if (get_called_class() === self::class) {
            return [
                'timestampBehavior' => [
                    'class' => TimestampBehavior::class,
                    'createdAtAttribute' => 'at',
                    'updatedAtAttribute' => false,
                ],
            ];
        } else {
            return [];
        }


    }
    public static function find()
    {
        return new PrisonerLocationJournalQuery(get_called_class());
    }
    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id' => 'prisoner_id']);
    }

    public function getPrison()
    {
        return $this->hasOne(Prison::class,['__company_id' => 'prison_id']);
    }
    public function getSector()
    {
        return $this->hasOne(Sector::class,['__ownableitem_id' => 'sector_id']);
    }

    public function getCell()
    {
        return $this->hasOne(Cell::class,['__ownableitem_id' => 'cell_id']);
    }



}
