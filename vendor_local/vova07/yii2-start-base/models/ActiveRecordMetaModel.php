<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/16/19
 * Time: 10:54 AM
 */

namespace vova07\base\models;


use Codeception\Lib\Generator\Helper;
use vova07\base\ModelGenerator\ModelsFactory;
use yii\db\ActiveRecord;


abstract class ActiveRecordMetaModel extends ActiveRecord
{
    private $_extraAttributes;

    /**
     * @var array =['column'=>value]]
     */
    public $loadedDependModels;

    public  static function  tableName()
    {
        return null;
    }

    public static function getMetadata()
    {
        return [
            'fields' => [],
            'primaries' => [],
            'indexes' => [],
            'foreignKeys' => [],
            'dependsOn' => []
        ];
    }

    public static function getMetadataForMerging()
    {
        return [
            'fields' => [],
            'primaries' => [],
            'indexes' => [],

            'foreignKeys' => []
        ];
    }

    public static function getMetadataByKeyField($key)
    {
        return self::getMetadata()[$key];
    }





    public function getExtraAttributes()
    {
        return $this->_extraAttributes;
    }

    /**
     * link related field
     */
    public function linkOne(ActiveRecord $model, $fieldName=null)
    {
       if (is_null($fieldName)) {
           $fieldName  = \vova07\base\ModelGenerator\Helper::getRelatedModelIdFieldName($model);
       }

       if (!$this->hasAttribute($fieldName)){
           throw new \LogicException("not found attribute " . $fieldName);
       }
       $className = get_class($model);
       $modelPrimaryKey = $className::primaryKey()[0];
       $this->$fieldName = $model->$modelPrimaryKey;
       $this->loadedDependModels[$fieldName] = $model;
    }

    public function hasLoadedDependModel($modelClassName)
    {
        $fieldName  = \vova07\base\ModelGenerator\Helper::getRelatedModelIdFieldName($modelClassName);
        return isset($this->loadedDependModels[$modelClassName]);

    }



/*
    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }*/

}