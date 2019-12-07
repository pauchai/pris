<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\prisons\models;



use vova07\base\ModelGenerator\Helper;
use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class CompanyDepartment extends  ActiveRecordMetaModel
{



    public static function tableName()
    {
        return 'company_department';
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                "company_id" => Schema::TYPE_INTEGER . ' NOT NULL',
                'department_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'order' => Schema::TYPE_TINYINT ,
            ],
            'primaries' => [
                [self::class, ['company_id','department_id']]
            ],
            //'indexes' => [
            //    [self::class, ['company_id','department_id'], true],
//          //      [self::class, ['company_id']],
//
            //],
            'foreignKeys' => [
                [get_called_class(), 'company_id',Company::class,Company::primaryKey()],
                [get_called_class(), 'department_id',Department::class,Department::primaryKey()],
            ],
            //'dependsOn' => [
            //    Company::class
            //]


        ];
        return $metadata;
    }

    public function rules()
    {
        return [
            [['company_id', 'department_id'],'safe']
        ];
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::class,['__ownableitem_id' => 'department_id']);
    }
    public function getCompany()
    {
        return $this->hasOne(Company::class,['__ownableitem_id' => 'company_id']);
    }








}
