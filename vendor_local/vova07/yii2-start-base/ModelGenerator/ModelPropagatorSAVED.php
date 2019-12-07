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



class ModelPropagator extends BaseObject
{
    const BASE_CLASS_NAME = ActiveRecordMetaModel::class;

    /**
     * @var array of attributes for parents
     */
   // public $extraAttributes=[];

    /**
     * @var ActiveRecordMetaModel model which saves
     */
    public $model;

    /**
     * @var array of attributeId => model
     */
    private $_relatedModels = [];


    /**
     * @param bool $save save $model after propagation or not
     * @return bool
     */
    public function save($model=null, $doValidation=false)
    {
        if ($model) {
            $this->model = $model;
            $this->_relatedModels = [];
        }

        return $this->propagateSave($model, $doValidation);
    }


    /**
     * @param ActiveRecordMetaModel $model
     * @param bool $save
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    private function propagateSave(ActiveRecordMetaModel $model,  $doValidation=false)
    {

        $parentModelClass = $this->shiftParentClassName($model);


        if ($parentModelClass) {
            $parentModel = \Yii::createObject($parentModelClass);
            $this->propagateSave($parentModel);
        }

        $modelClassName = get_class($model);
        if (isset($modelClassName::$dependsOn)){
            $dependedModel = \Yii::createObject($modelClassName::$dependsOn);
            $this->propagateSave($dependedModel);
        }

        foreach ($this->_relatedModels as $attrName => $relModel) {
              if ($model->hasAttribute($attrName) && !$model->getAttribute($attrName)){
                $model->$attrName = $relModel->id;
              }
        }

       // foreach ($this->extraAttributes as $extraAttributeName => $extraAttributeValue) {
       //     if ($model->hasAttribute($extraAttributeName))
       //         $model->$extraAttributeName = $extraAttributeValue;
       // }



            if ($model->save($doValidation)){
                $newModelRelatedAttribute = Helper::getRelatedModelIdFieldName(get_class($model));
                $this->_relatedModels[$newModelRelatedAttribute] = $model;
                return true;
            };





        return false;

    }

    /**
     * @param $classNameOrObj
     * @return bool|string className
     *
     */

    private function shiftParentClassName($classNameOrObj)
    {

        if (is_object($classNameOrObj)) {
            $className = get_class($classNameOrObj);
        }  else {
            $className = $classNameOrObj;
        }

        if ($className == self::BASE_CLASS_NAME) {
            return false;
        }
        $parentClassName =  get_parent_class(($className));
        if ($parentClassName == self::BASE_CLASS_NAME) {
            return false;
        }


        if ($className::tableName() == $parentClassName::tableName()){
            //skip closure inheritance like BackendUser and CommonUser
            $parentClassName = $this->shiftParentClassName($parentClassName);

        }
        $modelRelatedAttribute = Helper::getRelatedModelIdFieldName($parentClassName);

        if ($this->model->$modelRelatedAttribute) {
            //skip exists class
            $parentClassName = $this->shiftParentClassName($parentClassName);
        }

        return $parentClassName;

    }




}