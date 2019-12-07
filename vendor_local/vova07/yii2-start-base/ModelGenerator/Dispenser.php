<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/29/19
 * Time: 2:43 PM
 */

namespace vova07\base\ModelGenerator;


use vova07\base\models\ActiveRecordMetaModel;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use yii\base\BaseObject;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;

class Dispenser extends BaseObject
{
    /**
     * @param ActiveRecordMetaModel $model
     * @throws \yii\base\InvalidConfigException
     */
    public function run($model)
    {
        $dependsOn = get_class($model)::getDepends();

        foreach ($dependsOn as $dependOn)
        {

            if (!$model->hasLoadedDependModel($dependOn)){

                $dependModel = \Yii::createObject($dependOn);
                $this->run($dependModel);
            } else {
                $dependModel = $model->getLoadedDependModel($dependOn);
                $this->run($dependModel);
            }

            $link['model'] = $model;
            $link['column'] = Helper::getRelatedModelIdFieldName($dependModel);
            $link['refModel'] = $dependModel;
            $link['refColumn'] = $dependModel->primaryKey;

            // $model->loadedDepends[$dependModelName]=$dependModel;
            /**
             * @var ActiveRecord $refModel
             */
            if ($model->isNewRecord){
                $model->on(BaseActiveRecord::EVENT_BEFORE_INSERT, function($event){

                    //dependModel->save

                    $model= $event->data['model'];
                    $modelColumn = $event->data['column'];
                    $refModel = $event->data['refModel'];
                    $refColumn = $event->data['refColumn'];
                    /**
                     * @var ActiveRecord $refModel
                     */
                    if ($refModel->isNewRecord){
                        $refModel->save();
                    };

                    $model->$modelColumn = $refModel->$refColumn;



                }, $link);
            } else {
                $model->on(BaseActiveRecord::EVENT_BEFORE_UPDATE, function($event){

                    //dependModel->save

                    $model= $event->data['model'];
                    $modelColumn = $event->data['column'];
                    $refModel = $event->data['refModel'];
                    $refColumn = $event->data['refColumn'];
                    /**
                     * @var ActiveRecord $refModel
                     */
                    if ($refModel->dirtyAttributes){
                        $refModel->save();
                    };

                    $model->$modelColumn = $refModel->$refColumn;



                }, $link);

            }



        }
    }



}