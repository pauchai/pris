<?php

namespace vova07\plans\components;

class UpdateProgramAction extends \yii\base\Action
{

    public $getParam = 'id';


    public function run()
    {
        if (is_null($model = \vova07\plans\models\Program::findOne(\Yii::$app->request->get($this->getParam))))
        {
            throw new \yii\web\NotFoundHttpException(\vova07\plans\Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    return $this->controller->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                };
            };
        }

        return $this->controller->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }

}