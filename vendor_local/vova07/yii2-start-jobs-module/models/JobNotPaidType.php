<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/20/19
 * Time: 12:56 PM
 */

namespace vova07\jobs\models;



use vova07\base\models\ActiveRecordMetaModel;
use vova07\jobs\Module;
use yii\db\ActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;

class JobNotPaidType extends  ActiveRecordMetaModel
{



    public static function tableName()
    {
        return 'job_not_paid_types';
    }

    public function rules()
    {
        return [
            [['title'],'required'],
            [['title'], 'string'],
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
                'id' => Schema::TYPE_PK ,
                'title' => Schema::TYPE_STRING . ' NOT NULL',
            ],

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
        return new JobNotPaidTypeQuery(get_called_class());

    }


    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(),'id','title');

    }



}