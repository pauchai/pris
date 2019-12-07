<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/20/19
 * Time: 12:56 PM
 */

namespace vova07\humanitarians\models;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\humanitarians\Module;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;

class HumanitarianIssue extends  Ownableitem
{

    public static function tableName()
    {
        return 'humanitarian_issues';
    }

    public function rules()
    {
        return [
            [['dateIssueJui'], 'date','format'=>Module::getInstance()->dateFormat],
            [['items'],'safe'],
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
                'date_issue' => Schema::TYPE_INTEGER . ' NOT NULL',
                'items' => $migration->json(),
            ],
            'indexes' => [
                [self::class, 'date_issue'],
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());

    }

    public function behaviors()
    {


            $behaviors = [

                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'ownableitem',
                    ],
                ],
                [
                    'class' => DateJuiBehavior::class,
                    'attribute' => 'date_issue',
                    'juiAttribute' => 'dateIssueJui',

                ]

            ];

        return $behaviors;
    }



    public static function find()
    {
        return new HumanitarianItemQuery(get_called_class());

    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }

    public function getItems()
    {
        return HumanitarianItem::find()->andWhere(['in','__ownableitem_id',$this->items])->all();
    }
    public function attributeLabels()
    {
       return  [
        'dateIssueJui' => Module::t('labels','DATE_START_LABEL')
        ];
    }

}