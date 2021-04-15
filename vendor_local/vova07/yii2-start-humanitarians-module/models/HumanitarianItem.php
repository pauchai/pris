<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/20/19
 * Time: 12:56 PM
 */

namespace vova07\humanitarians\models;


use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\humanitarians\Module;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class HumanitarianItem extends  Ownableitem
{

    public static function tableName()
    {
        return 'humanitarian_items';
    }

    public function rules()
    {
        return [
            [['title'], 'required'],
            [['sort_weight'], 'integer']
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
                'sort_weight' => $migration->integer()
            ],
            'indexes' => [
                [self::class, 'title'],
                [self::class, 'weight']
            ],

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

        return (new HumanitarianItemQuery(get_called_class()))->orderBy(['sort_weight' => SORT_ASC]);

    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(), '__ownableitem_id', 'title');
    }
    public function attributeLabels()
    {
        return [
          'title' => Module::t('labels','TITLE_LABEL')
        ];
    }
}