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
use yii\db\Schema;


class WorkDay extends  ActiveRecordMetaModel
{


    public static function tableName()
    {
        return 'work_days';
    }

    public function rules()
    {
        return [
            [['day_date'],'required'],
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
                'day_date' => Schema::TYPE_DATE . ' NOT NULL',

            ],
            //'indexes' => [
            //    [self::class, 'date_issue'],
            //],
            'primaries' => [
                [ self::class, ['day_date']]
            ],
            'foreignKeys' => [
                [self::class, 'day_date',Holiday::class,Holiday::primaryKey()],
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
        return new WorkDayQuery(get_called_class());
    }




}