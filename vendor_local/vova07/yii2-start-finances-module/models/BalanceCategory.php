<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/20/19
 * Time: 12:56 PM
 */

namespace vova07\finances\models;



use vova07\base\models\ActiveRecordMetaModel;
use vova07\finances\Module;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class BalanceCategory extends  ActiveRecordMetaModel
{
    const CATEGORY_DEBIT_SALARY = 1;
    const CATEGORY_DEBIT_CASSA = 2;
    const CATEGORY_DEBIT_BANK = 3;
    const CATEGORY_DEBIT_OTHER = 4;
    const CATEGORY_CREDIT_SHOP = 5;
    const CATEGORY_CREDIT_RELATIONS = 6;
    const CATEGORY_CREDIT_LIBERATION = 7;
    const CATEGORY_CREDIT_OTHER = 8;

    public static function tableName()
    {
        return 'balance_categories';
    }

    public function rules()
    {
        return [
            [['type_id','category_id','title','short_title'],'required'],
            [['title','short_title'], 'string'],
        ];

    }


    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
              //  Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'type_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'title' => Schema::TYPE_STRING . ' NOT NULL',
                'short_title' => Schema::TYPE_STRING,

            ],
            'primaries' => [
                [self::class,['type_id','category_id']]
            ]
            //'indexes' => [
            //    [self::class, 'date_issue'],
            //],

        ];
        return $metadata;

    }

    public function behaviors()
    {


            $behaviors = [


            ];

        return $behaviors;
    }



    public static function find()
    {
        return new BalanceCategoryQuery(get_called_class());

    }


    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(),'category_id','short_title');

    }




}