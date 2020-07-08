<?php

namespace vova07\base\components;

use yii\web\NotFoundHttpException;

class EditableColumnAction extends \kartik\grid\EditableColumnAction
{
    public function findModel($id)
    {
        $id = get_object_vars(json_decode($id));
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $id, $this);
        }

        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;


         $model = $modelClass::findOne($id);


        if (isset($model)) {
            return $model;
        }

        throw new NotFoundHttpException("Object not found: $id");
    }

}
