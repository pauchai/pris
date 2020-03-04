<?php
namespace vova07\concepts\controllers\backend;
use vova07\base\components\BackendController;
use vova07\concepts\models\Concept;
use vova07\concepts\models\ConceptClass;
use vova07\concepts\models\ConceptParticipant;
use vova07\concepts\models\ConceptVisit;
use vova07\concepts\Module;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class VisitsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_CONCEPT_VISIT_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_CONCEPT_VISIT_CREATE]
            ],
        ];
        return $behaviors;
    }

    public function actionCreate()
    {
        $model = new ConceptVisit();
        $key = ArrayHelper::filter(\Yii::$app->request->post(),['class_id','prisoner_id']);
        if (!($model = ConceptVisit::findOne($key))){
            $model = new ConceptVisit;
            $model->load(\Yii::$app->request->post(),'');
            if ($model->validate()){
                if ($model->save()){
                    // return $this->renderPartial('view_ajax',['model' => $model]);
                }
            }
        }

        return $this->renderPartial('view_ajax',['model'=>$model]);

    }

    public function actionDelete($id)
    {
        if (is_null($model = ProgramVisit::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }
}