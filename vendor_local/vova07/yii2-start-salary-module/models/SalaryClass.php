<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\salary\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Ownableitem;
use vova07\salary\Module;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;



class SalaryClass extends  ActiveRecordMetaModel
{


    public static function tableName()
    {
        return 'salary_class';
    }
    public function rules()
    {
        return [

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
                'id' => $migration->smallInteger()->notNull(),
                'rate' => $migration->double('1,1'),
                'level' => $migration->tinyInteger(2),

            ],

            [
                [self::class, 'prisoner_id'],
            ],
            'primaries' => [
                [self::class, ['id']]
            ],

           // 'foreignKeys' => [
           //     [get_called_class(), ['officer_id'],Officer::class, Officer::primaryKey()]
           // ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );

    }


    public static function find()
    {
        return new RateQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }







}
