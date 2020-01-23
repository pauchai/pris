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

class JobPaidType extends  ActiveRecordMetaModel
{

    const COMPENSATION_3PER4 = 1;
    const COMPENSATION_2PER3 = 2;

    const CATEGORY_1 = 1;
    const CATEGORY_2 = 2;
    const CATEGORY_3 = 3;
    const CATEGORY_4 = 4;
    const CATEGORY_5 = 5;
    const CATEGORY_6 = 6;
    const CATEGORY_7 = 7;
    const CATEGORY_8 = 8;

    public static function tableName()
    {
        return 'job_paid_types';
    }

    public function rules()
    {
        return [
            [['title','compensation_id', 'category_id', 'hours_per_day','hours_per_sa'],'required'],
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
                'compensation_id' => Schema::TYPE_TINYINT . ' NOT NULL',
                'category_id' => Schema::TYPE_TINYINT. ' NOT NULL',
                'hours_per_day' => Schema::TYPE_DECIMAL . ' NOT NULL',
                'hours_per_sa' => Schema::TYPE_DECIMAL . ' NOT NULL',
            ],
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
        return new JobPaidTypeQuery(get_called_class());

    }

    public static function getCompensationsForCombo()
    {
        return [
            self::COMPENSATION_3PER4 => Module::t('default','JOB_PAID_COMPENSATION_3PER4_TITLE'),
            self::COMPENSATION_2PER3 => Module::t('default','JOB_PAID_COMPENSATION_2PER3_TITLE'),
        ];
    }

    public function getCompensationTitle()
    {
        return self::getCompensationsForCombo()[$this->compensation_id];
    }

    public static function getCompensationRatios()
    {
        return [
            self::COMPENSATION_3PER4 => 1/3,
            self::COMPENSATION_2PER3 => 1/2,
        ];
    }
    public function getCompensationRatio()
    {
        return self::getCompensationRatios()[$this->compensation_id];
    }

    public static function getCategoryForCombo()
    {
        return [
            self::CATEGORY_1 => 'I',
            self::CATEGORY_2 => 'II',
            self::CATEGORY_3 => 'III',
            self::CATEGORY_4 => 'IV',
            self::CATEGORY_5 => 'V',
            self::CATEGORY_6 => 'VI',
            self::CATEGORY_7 => 'VII',
            self::CATEGORY_8 => 'VIII'
        ];
    }
    public  function getCategoryTitle()
    {
        return self::getCategoryForCombo()[$this->category_id];
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->asArray()->all(),'id','title');

    }
    public function attributeLabels()
    {
        return [
          'title' => Module::t('labels','JOB_TITLE_LABEL')
        ];
    }


}