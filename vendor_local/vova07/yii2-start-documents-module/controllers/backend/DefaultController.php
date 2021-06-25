<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\documents\controllers\backend;



use vova07\base\components\BackendController;
use vova07\documents\models\backend\DocumentSearch;
use vova07\documents\models\Document;
use vova07\documents\Module;
use vova07\users\models\Person;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
class DefaultController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_DOCUMENTS_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_DOCUMENT_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_DOCUMENT_UPDATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_DOCUMENT_DELETE]
            ],
            [
                'allow' => true,
                'actions' => ['view'],
                'roles' => [\vova07\rbac\Module::PERMISSION_DOCUMENT_VIEW]
            ],
            [
                'allow' => true,
                'actions' => ['person-documents'],

            ],
        ];
        return $behaviors;
    }

    public function actions()
    {
        return [
            'person-documents' => [
                'class' => \kartik\depdrop\DepDropAction::class,
                'outputCallback' => function ($selectedId, $params) {
                    return Document::getDocumentsArray(Person::findOne($selectedId)->getDocuments());

                }
            ]
        ];
    }
    public function actionIndex()
    {
        \Yii::$app->user->returnUrl = Url::current();
        $searchModel = new DocumentSearch();
        $searchModel->status_id = DocumentSearch::STATUS_ACTIVE;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $dataProvider->query->activePrisoners();
        if ($this->isPrintVersion)
                $dataProvider->pagination = false;
        $dataProvider->sort->defaultOrder = [

            'person.second_name' => SORT_ASC,
            'type_id' => SORT_ASC,

        ];
        return $this->render("index", ['searchModel'=>$searchModel,'dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {
        $model = new Document();


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->goBack();
                }
            };
        }
        foreach($model->getErrorSummary(true) as $errorStr){
            \Yii::$app->session->setFlash("error",$errorStr);
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionView($id)
    {
        if (is_null($model = Document::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        return $this->render('view', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = Document::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->redirect(['index']);
        };
            throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

    public function actionUpdate($id)
    {
        if (is_null($model = Document::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate()){
                if ($model->save()){
                    return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
                };
            };
        }

        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }




}