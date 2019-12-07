<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/2/19
 * Time: 1:39 PM
 */

namespace vova07\base\ModelGenerator;


use vova07\base\models\ActiveRecordMetaModel;
use yii\base\BaseObject;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;



class ModelTableGenerator extends BaseObject
{
    //const BASE_CLASS_NAME = ActiveRecordMetaModel::class;
    private static $_relations = [];
    private static $_indexes = [];
    private static $_primaries = [];
    private $_generatedModels = [];


    /**
     * @var \yii\db\Connection
     */
    public $db;
    public function init()
    {
        if (is_null($this->db)){
            $this->db = \Yii::$app->db;
        }
    }

    public function  generateTable($modelClassName)
    {
       $models = $this->generateTableForModel($modelClassName);
        //$this->addRelations($models);
    }

    public function addRelations($model, $relModels)
    {
        if ($relModels){
            foreach($relModels as $refModel){

                $this->addRelation($model, $refModel);

            }
        }
    }

    private function shiftParentClass($className)
    {
       $parentClassName = get_parent_class($className);
       if ($parentClassName === false) {
           return false;
       }
       if (Helper::checkClassInheritsFromBasePlusIncluded($parentClassName) ) {
           if ($className::tableName() === $parentClassName::tableName() OR is_null($parentClassName::tableName())) {
               return  $this->shiftParentClass($parentClassName);
           } else {
               return $parentClassName;
           }

       }
        return false;

     }

    /**
     * @param $modelClassName
     * @param bool $cleanStack
     */
    private function generateTableForModel($modelClassName, $cleanStack = true)
    {
      //  static $generatedModels;

       // if ($cleanStack)
       //         $generatedModels = [];

        if (!Helper::checkClassInheritsFromBaseAndHasTable($modelClassName)){
            throw new \LogicException(" class doesnt has ability to create table");
        }


        $parentClassName = $this->shiftParentClass($modelClassName);


            $dependsOn = $modelClassName::getMetaData()['dependsOn'] ?? [];
            if ($parentClassName) {
                $dependsOn[] = $parentClassName;
            }

            if (!isset($this->_generatedModels[$modelClassName])) {

                foreach ($dependsOn as $dependOn)
                {
                    $this->generateTableForModel($dependOn);
                }

                if ($this->createTableForModelIfDoesntExists($modelClassName)){
                    $this->_generatedModels[$modelClassName] = true;
                    foreach ($dependsOn as $dependOn)
                    {
                        $this->addRelation($modelClassName,  $dependOn);
                    }

                };

            }


        return $modelClassName;
    }

    private function addRelation($className, $refClassName)
    {
        $column = Helper::getRelatedModelIdFieldName($refClassName);

        $refTableSchema = $this->db->getTableSchema($refClassName::tableName());
        if (!$refTableSchema){
            throw new \LogicException("table doesnt exists");

        }

        $refColumn = $refTableSchema->primaryKey;
        //$Column = Inflector::camel2id(StringHelper::basename($refClassName), '_') . '_id';

        $tableSchema = $this->db->getTableSchema($className::tableName());
        if (!$tableSchema->getColumn($column)) {
            $this->db->createCommand()->addColumn($className::tableName(), $column, Schema::TYPE_INTEGER . ' not null')->execute();
        }
            $this->addRelationByColumns($className, $column, $refClassName, $refColumn);
            $this->createIndex($className,$column,true);





    }

    private function createIndex($className, $columns, $unique=false, $name=null)
    {

        if (is_null($name)) {
            if (is_array($columns)) {
                $columnsJoined = Join('_', $columns);
            } else {
                $columnsJoined = $columns;
            }
            $name = $columnsJoined;
        }
        $indexName = 'idx_' . Helper::getTableNameByModelClass($className) . '_' . $name;
        if (!isset(static::$_indexes[$indexName])){
                $this->db->createCommand()->createIndex($indexName, $className::tableName(),$columns, $unique)->execute();
                static::$_indexes[$indexName] = true;
        }

    }

    private function createPrimaryIndex($className, $columns)
    {

        if (is_array($columns)){
            $columnsJoined = Join('_', $columns);
        } else {
            $columnsJoined = $columns;
        }
        $indexName = 'idx_' . Helper::getTableNameByModelClass($className) . '_' . $columnsJoined;
        if (!isset(static::$_primaries[$indexName])){
            //$this->db->createCommand()->createIndex($indexName, $className::tableName(),$columns, $unique)->execute();
            $this->db->createCommand()->addPrimaryKey($indexName, $className::tableName(),$columns)->execute();
            static::$_primaries[$indexName] = true;
        }

    }

    private  function addRelationByColumns($className,$columns,$refClassName, $refColumns)
    {
        $columnsStr = is_array($columns)?join('_',$columns):$columns;
        $tableName = Helper::getTableNameByModelClass($className);
        $refTableName = Helper::getTableNameByModelClass($refClassName);
        $refColumnsStr = is_array($refColumns)?join('_',$refColumns):$refColumns;
        //$fkName = "fk" . $tableName . '_' . $columnsStr. "_" . $refTableName . '_' . $refColumnsStr ;
        $fkName = "fk_" . $tableName . crc32($columnsStr. "_" . $refTableName . '_' . $refColumnsStr );
        if (!isset(static::$_relations[$fkName]))
        {
            \Yii::$app->db->createCommand()->addForeignKey($fkName,  $className::tableName() ,$columns, $refClassName::tableName(),$refColumns, 'cascade')->execute();
            static::$_relations[$fkName] = true;
        }

    }

    private function createTableForModelIfDoesntExists($modelClassName)
    {
        $tableSchema = $this->db->getTableSchema($modelClassName::tableName());
        if (!$tableSchema){
            $this->createTableForModel($modelClassName);
            return true;
        } else {
            return false;
        }

    }
    /**
     * @param $modelClassName
     * @param array $extraFields
     * @return mixed
     * @throws \yii\db\Exception
     */
    private function createTableForModel($modelClassName, $extraFields = [])
    {
        $metadata = $modelClassName::getMetadata();
        $columns = $metadata['fields'];
        $columns = ArrayHelper::merge($columns, $extraFields);

        $table = $modelClassName::tableName();




            $tableOptions = null;
        // MySql table options
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->db->createCommand()->createTable($modelClassName::tableName(), $columns, $tableOptions)->execute();
        foreach ($columns as $column => $type) {
            if ($type instanceof ColumnSchemaBuilder && $type->comment !== null) {
                $this->db->createCommand()->addCommentOnColumn($table, $column, $type->comment)->execute();
            }
        }
        if (array_key_exists('primaries', $metadata)){
            $indexes  = $metadata['primaries'];
            foreach ($indexes as $indexArr){
                //  call_user_func_array([$db->createCommand(),'createIndex'], $indexArr);
                $this->createPrimaryIndex(...$indexArr);
                //$this->db->createCommand()->createIndex(...$indexArr)->execute();
            }
        }

        if (array_key_exists('indexes', $metadata)){
            $indexes  = $metadata['indexes'];
            foreach ($indexes as $indexArr){
                //  call_user_func_array([$db->createCommand(),'createIndex'], $indexArr);
                $this->createIndex(...$indexArr);
                //$this->db->createCommand()->createIndex(...$indexArr)->execute();
            }
        }
        if (array_key_exists('foreignKeys', $metadata)) {
            $foreignKeys = $metadata['foreignKeys'];
            foreach ($foreignKeys as $foreignKey){
                $this->addRelationByColumns(...$foreignKey);
            }
        }
        /*
        if (isset($modelClassName::$dependsOn)){
            $this->addRelation($modelClassName,$modelClassName::$dependsOn);
        }
        */

        return $modelClassName::tableName();
    }
}