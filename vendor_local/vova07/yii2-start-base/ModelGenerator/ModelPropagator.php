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

        $modelClassName = get_class($model);
        $parentModelClass = $this->shiftParentClassName($model);


        $dependsOn = $modelClassName::getMetaData()['dependsOn'] ?? [];

        if ($parentModelClass) {
            $dependsOn[] = $parentModelClass;

        }

        foreach ($dependsOn as $dependOn)
        {
            if (!$model->hasLoadedDependModel($dependOn)){
                $dependModel = \Yii::createObject($dependOn);
                $this->propagateSave($dependModel);
                $model->linkOne($dependModel);
            }

        }


         return $model->save($doValidation);

    }

    /**
     * @param $classNameOrObj
     * @return bool|string className
     *
     */
    private function shiftParentClassName($classNameOrObj)
    {
        return Helper::shiftParentClass($classNameOrObj);
    }





}