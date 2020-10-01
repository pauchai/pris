<?php

namespace vova07\base\components;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class EditableColumnAction extends \kartik\grid\EditableColumnAction
{
    public $attributeNamesForRefresh;
    protected function validateEditable()
    {
        $request = \Yii::$app->request;
        if ($this->postOnly && !$request->isPost || $this->ajaxOnly && !$request->isAjax) {
            throw new BadRequestHttpException('This operation is not allowed!');
        }
        $this->initErrorMessages();
        $post = $request->post();
        if (!isset($post['hasEditable'])) {
            return ['output' => '', 'message' => $this->errorMessages['invalidEditable']];
        }
        /**
         * @var ActiveRecord $model
         */
        $key = ArrayHelper::getValue($post, 'editableKey');
        $model = $this->findModel($key);
        if (!$model) {
            return ['output' => '', 'message' => $this->errorMessages['invalidModel']];
        }
        if ($this->checkAccess && is_callable($this->checkAccess)) {
            call_user_func($this->checkAccess, $this->id, $model);
        }
        $model->scenario = $this->scenario;
        $index = ArrayHelper::getValue($post, 'editableIndex');
        $attribute = ArrayHelper::getValue($post, 'editableAttribute');
        $formName = isset($this->formName) ? $this->formName: $model->formName();
        if (!$formName || is_null($index) || !isset($post[$formName][$index])) {
            return ['output' => '', 'message' => $this->errorMessages['editableException']];
        }
        $postData = [$model->formName() => $post[$formName][$index]];
        if ($model->load($postData)) {
            $params = [$model, $attribute, $key, $index];
            $message = static::parseValue($this->outputMessage, $params);
            if (!$model->save()) {
                if (!$model->hasErrors()) {
                    return ['output' => '', 'message' => $this->errorMessages['saveException']];
                }
                if (empty($message) && $this->showModelErrors) {
                    $message = Html::errorSummary($model, $this->errorOptions);
                }
            }
            $value = static::parseValue($this->outputValue, $params);
            $modelAttributes = $model->getAttributes($this->attributeNamesForRefresh);
            return ['output' => $value, 'attributes' => $modelAttributes, 'message' => $message];
        }
        return ['output' => '', 'message' => ''];
    }


}
