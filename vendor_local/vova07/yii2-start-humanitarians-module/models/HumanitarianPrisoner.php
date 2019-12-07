<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\humanitarians\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;

use vova07\base\models\Ownableitem;

use vova07\plans\Module;

use vova07\prisons\models\Sector;
use vova07\users\models\Officer;


use vova07\users\models\Prisoner;
use yii\db\Schema;
use yii\helpers\ArrayHelper;




class HumanitarianPrisoner extends  Ownableitem
{

    public static function tableName()
    {
        return 'humanitarians';
    }

    public function rules()
    {
        return [
            [['prisoner_id', 'item_id','issue_id'], 'required'],
            //[['dateIssueJui'], 'date','format'=>$this->getModule()->dateFormat],

        ];

    }

    /**
     * @return null|\vova07\humanitarians\Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('humanitarian');
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
               // Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'issue_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'prisoner_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'item_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            ],
            'indexes' => [
                [self::class, 'issue_id'],
                [self::class, 'item_id'],
                [self::class, 'prisoner_id'],

            ],
            'foreignKeys' => [
                [self::class, 'issue_id', HumanitarianIssue::class, HumanitarianIssue::primaryKey()],
                [self::class, 'item_id', HumanitarianItem::class, HumanitarianItem::primaryKey()],
                [self::class, 'prisoner_id', Prisoner::class, Prisoner::primaryKey()],

            ],
            'primaries' => [
                [self::class,['issue_id','prisoner_id','item_id']]
            ]

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
        return new HumanitarianPrisonerQuery(get_called_class());

    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id' => 'prisoner_id']);
    }

    public function getHumanitarianItem()
    {
        return $this->hasOne(HumanitarianItem::class,['__ownableitem_id' => 'item_id']);
    }
    public function getHumanitarianIssue()
    {
        return $this->hasOne(HumanitarianIssue::class,['__ownableitem_id' => 'issue_id']);
    }




}

