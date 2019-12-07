<?php

namespace vova07\base\ModelGenerator;
use Codeception\Module\Yii2;
use vova07\base\models\ActiveRecordMetaModel;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/20/19
 * Time: 4:16 PM
 */

class ModelsFactory
{
    const BASE_CLASS_NAME = ActiveRecordMetaModel::class;

    public static $modelClasses = [];
    public static function generateTableForModel($modelClassName)
    {
        $metadata = $modelClassName::getMetadata();
        $columns = $metadata['fields'];

        $table = $modelClassName::tableName();
        $db = \Yii::$app->db;


        $tableOptions = null;
        // MySql table options
        if ($db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $db->createCommand()->createTable($modelClassName::tableName(), $columns, $tableOptions)->execute();
        foreach ($columns as $column => $type) {
            if ($type instanceof ColumnSchemaBuilder && $type->comment !== null) {
                $db->createCommand()->addCommentOnColumn($table, $column, $type->comment)->execute();
            }
        }

        $indexes  = $metadata['indexes'];
        foreach ($indexes as $indexArr){
            //  call_user_func_array([$db->createCommand(),'createIndex'], $indexArr);
            $db->createCommand()->createIndex(...$indexArr)->execute();
        }
        if (array_key_exists('foreignKeys', $metadata)) {
            $foreignKeys = $metadata['foreignKeys'];
            foreach ($foreignKeys as $foreignKey){
                self::addRelation($modelClassName, ...$foreignKey);
            }
        }

        return $modelClassName;
    }

    public static function addRelation($className,$columns,$refClassName, $refColumns)
    {
        $columnsStr = is_array($columns)?join('_',$columns):$columns;
        $tableName = Inflector::camel2id(StringHelper::basename($className), '_');
        $refTableName = Inflector::camel2id(StringHelper::basename($refClassName), '_');
        $refColumnsStr = is_array($refColumns)?join('_',$refColumns):$refColumns;
        $fkName = "fk_" . $tableName . '_' . $columnsStr. "_" . $refTableName . '_' . $refColumnsStr ;
        \Yii::$app->db->createCommand()->addForeignKey($fkName,  $className::tableName() ,$columns, $refClassName::tableName(),$refColumns)->execute();
    }

    public static function createTablesForModels($models)
    {
        foreach($models as $model) {
            self::generateTableForModel($model);
        }
    }

    public static function createTableForModel($modelClass)
    {

        $classParents = class_parents($modelClass);
        if ( $modelClass!=self::BASE_CLASS_NAME){
            if (ArrayHelper::isIn(self::BASE_CLASS_NAME, $classParents)  )
            {
                self::createTableForModel(get_parent_class($modelClass));

            }
            self::$modelClasses[] = self::generateTableForModel($modelClass);

            self::addRelation($modelClass);
            return;
        }





    }
    public static function dropTablesForModels($models)
    {
        $db = \Yii::$app->db;
        foreach($models as $model) {
            if ($db->getTableSchema($model::tableName(), true)) {
                $db->createCommand()->dropTable($model::tableName())->execute();
            }
        }

      //  $dataFk = (new Query())->select(['CONSTRAINT_NAME', 'TABLE_SCHEMA','TABLE_NAME','COLUMN_NAME', 'REFERENCED_TABLE_SCHEMA','REFERENCED_TABLE_NAME','REFERENCED_COLUMN_NAME',])
     //       ->from('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')
            //->where('TABLE_SCHEMA="helpex_new" AND REFERENCED_TABLE_SCHEMA IS NOT NULL')->one();
      //  codecept_debug(var_export($dataFk,true));

        /*
        $dataFk = (new Query())->select(['TABLE_SCHEMA','TABLE_NAME','COLUMN_NAME','CONSTRAINT_NAME'])
            ->from('INFORMATION_SCHEMA.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA="helpex_new" AND REFERENCED_TABLE_SCHEMA IS NOT NULL')->one();
        codecept_debug(var_export($dataFk,true));

        $dataFkTables = (new Query())->select('*')
            ->from('INFORMATION_SCHEMA.INNODB_SYS_FOREIGN')
            ->where(['ID' => $dataFk['TABLE_SCHEMA'] .'/' . $dataFk['CONSTRAINT_NAME']])->all();
        codecept_debug(var_export($dataFkTables,true));

        $dataFkCols= (new Query())->select('*')
            ->from('INFORMATION_SCHEMA.INNODB_SYS_FOREIGN_COLS')
            ->where(['ID' => $dataFk['TABLE_SCHEMA'] .'/' . $dataFk['CONSTRAINT_NAME']])->all();
        codecept_debug(var_export($dataFkCols,true));*/
    }

    /**
     * Save $model and resolve creating parents
     * @param $model,
     * @param $extraAttributes,
     * @param $save,
     * @return array of relatedAttributes
     */
    public static function propagateModelSave(ActiveRecordMetaModel $model, $extraAttributes, $save=true)
    {
        static $modelRelatedAttributes;

        if (!isset($modelRelatedAttributes)) {
            $modelRelatedAttributes = [];
        }
        if (get_class($model) == self::BASE_CLASS_NAME) {
            return $modelRelatedAttributes;
        }
        $parentModelClass = get_parent_class($model);

        if (get_class($model)::tableName() == $parentModelClass::tableName()){ //skip this class
             $parentModel = \Yii::createObject(get_parent_class($parentModelClass));
             $parentRelatedAttributes = self::propagateModelSave($parentModel, $extraAttributes);
        }



        $modelRelatedAttribute = Helper::getRelatedModelIdFieldName($parentModelClass);
        if ((get_class($model)::tableName() <> $parentModelClass::tableName()) AND ($parentModelClass <> self::BASE_CLASS_NAME) ) {

            if ($model->$modelRelatedAttribute) {
                $parentModel = \Yii::createObject(get_parent_class($parentModelClass));
                $parentRelatedAttributes = self::propagateModelSave($parentModel, $extraAttributes);
                $modelRelatedAttributes = ArrayHelper::merge($modelRelatedAttributes, $parentRelatedAttributes);
                $modelRelatedAttributes[$modelRelatedAttribute] = $parentModel->id;
                $save = false;

            } else {

                $parentModel = \Yii::createObject($parentModelClass);
                $parentRelatedAttributes = self::propagateModelSave($parentModel, $extraAttributes);
                $modelRelatedAttributes = ArrayHelper::merge($modelRelatedAttributes, $parentRelatedAttributes);
                $modelRelatedAttributes[$modelRelatedAttribute] = $parentModel->id;
                foreach ($modelRelatedAttributes as $attrName => $attrValue) {
                    //  if ($model->hasAttribute($attrName)){
                    $model->$attrName = $attrValue;
                    //  }
                }

                foreach ($extraAttributes as $extraAttributeName => $extraAttributeValue) {
                    if ($model->hasAttribute($extraAttributeName))
                        $model->$extraAttributeName = $extraAttributeValue;
                }
            }
        }

        if ($save) {
            $model->save(false);
        }

        return $modelRelatedAttributes;
    }

    /**
     * @param $model
     * @return ModelPropagator
     * @throws \yii\base\InvalidConfigException
     */
    public static function createModelPropagator($model)
    {
        return \Yii::createObject(['class' => ModelPropagator::class, 'model' => $model]);
    }



}