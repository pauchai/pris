<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\documents\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

/**
  * Class Company
 * @package vova07\prisons\models
 *
 * @property string $title Title
 */

class Blank extends  Ownableitem
{
    const TYPE_SOCIAL_ASSESSMENT_SHEET = 1;
    const TYPE_PSYCHOSOCIAL_INVESTIGATION = 2;
    public static function tableName()
    {
        return 'blank';
    }
    public function rules()
    {
        return [
            [['id','content','title','slug'], 'required'],
            [['department_id'],'safe']
        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                'id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'content' => Schema::TYPE_TEXT . ' NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'slug' => Schema::TYPE_STRING . " NOT NULL"
            ],
            'indexes' => [

            ],
            'primaries' => [
                [self::class, ['id']]
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
        return new BlankQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }


    /*
    public function beforeValidate()
    {
        return $this->beforeSaveActiveRecordMetaModel(true);
    }*/




    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(),'id','title');
    }




}
