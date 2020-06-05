<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/6/19
 * Time: 10:04 AM
 */

namespace vova07\base\ModelGenerator;


use vova07\base\models\ActiveRecordMetaModel;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

class Helper
{
    const BASE_CLASS_NAME = ActiveRecordMetaModel::class;

    public static function getRelatedModelIdFieldName($classNameOrModel)
    {
        if (is_object($classNameOrModel))
                $classNameOrModel = get_class($classNameOrModel);

        return '__' . Inflector::camel2id(StringHelper::basename($classNameOrModel), '_') . '_id';
    }
    public static function getTableNameByModelClass($className)
    {
        //return Inflector::camel2id(StringHelper::basename($className), '_');
        return $className::tableName();
    }

    public static function checkClassInheritsFromBase($className)
    {
        $classParents = class_parents($className);
        if (ArrayHelper::isIn(self::BASE_CLASS_NAME,  $classParents))
        {
            return true;
        }
        return false;
    }
    public static function checkClassInheritsFromBaseAndHasTable($className)
    {
        return self::checkClassInheritsFromBase($className) && !is_null($className::tableName());
    }
    public static function checkClassInheritsFromBasePlusIncluded($className)
    {
       if ($className<>self::BASE_CLASS_NAME){
            return self::checkClassInheritsFromBase($className);
        }
        return false;
    }

    public static function shiftParentClass($className)
    {
        $parentClassName = get_parent_class($className);
        if ($parentClassName === false) {
            return false;
        }
        if (Helper::checkClassInheritsFromBasePlusIncluded($parentClassName ) ) {
            if ($className::tableName() === $parentClassName::tableName() OR is_null($parentClassName::tableName())) {
                return  self::shiftParentClass($className);
            } else {
                return $parentClassName;
            }

        }
        return false;

    }

    public static function dropTablesForModels($modelClasses)
    {
        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=0')->execute();
        foreach ($modelClasses as $modelClass){
            if (\Yii::$app->db->getTableSchema($modelClass::tableName()))
                \Yii::$app->db->createCommand('DROP TABLE ' . $modelClass::tableName())->execute();

        }

        \Yii::$app->db->createCommand('SET FOREIGN_KEY_CHECKS=1')->execute();

    }

}