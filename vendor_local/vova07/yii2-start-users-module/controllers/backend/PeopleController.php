<?php
namespace vova07\users\controllers\backend;
use budyaga\cropper\actions\UploadAction;
use vova07\base\components\BackendController;
use vova07\users\models\backend\PersonSearch;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use vova07\users\models\Person;
use vova07\users\Module;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class PeopleController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','create','view','delete','update','upload-photo','upload-preview-photo'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actions()
    {
        return [
            'upload-photo' => [
                'class' => UploadAction::class,
                'url' => $this->module->personPhotoUrl,
                'path' => \Yii::getAlias($this->module->personPhotoPath),
            ],
            'upload-preview-photo' => [
                'class' => UploadAction::class,
                'url' => $this->module->personPreviewUrl,
                'path' => \Yii::getAlias($this->module->personPreviewPath),
            ]
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PersonSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        return $this->render("index", ['dataProvider'=>$dataProvider]);
    }

    public function actionCreate()
    {
        $model = new Person();
        $model->ident = new Ident();


        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                return $this->redirect(['view', 'id'=>$model->getPrimaryKey()]);
            }
        }

        return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionView($id)
    {
        if (is_null($model = Person::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        return $this->render('view', ['model'=>$model]);
    }
    public function actionDelete($id)
    {
        if (is_null($model = Person::findOne($id)))
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
        if (is_null($model = Person::findOne($id)))
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