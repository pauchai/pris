<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/25/19
 * Time: 10:40 AM
 */

namespace vova07\prisons\controllers\backend;


use vova07\base\components\BackendController;
use vova07\prisons\models\Division;
use vova07\prisons\models\OfficerPostView;
use vova07\prisons\models\Post;
use vova07\prisons\models\OfficerPost;
use vova07\prisons\models\backend\OfficerPostSearch;
use vova07\users\models\Ident;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class OfficerPostsController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index', 'officer-posts', 'officer-create', 'officer-view', 'create','view','delete','update'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }


    public function actionIndex()
    {

        $searchModel = new OfficerPostSearch();
        $searchModel->company_id = \Yii::$app->base->company->primaryKey;
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $newModel = new OfficerPost();
        $newModel->attributes = $searchModel->getAttributes(['officer_id', 'company_id','division_id','postdict_id']);
        $newModel->full_time = true;
        \Yii::$app->user->setReturnUrl(Url::current());

        return $this->render("index", ['dataProvider'=>$dataProvider, 'searchModel' => $searchModel, 'newModel' => $newModel]);
    }
    public function actionOfficerPosts()
    {
        $dataProvider = new ActiveDataProvider(
        [
          'query' => OfficerPostView::find()->orderBy(['second_name' => SORT_ASC, 'first_name' => SORT_ASC, 'patronymic' => SORT_ASC]),
            'pagination' => false
        ]
        );
        \Yii::$app->user->setReturnUrl(Url::current());

        return $this->render("officer_posts", ['dataProvider'=>$dataProvider]);


    }

    public function actionCreate($company_id, $officer_id)
    {

        $model = new OfficerPost([
            'company_id' => $company_id,
            'officer_id' => $officer_id
        ]);


        if (\Yii::$app->request->isPost){
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()){
                if (\Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => true];
                }
                return $this->goBack();
            }
        }

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax("create", ['model' => $model,'cancelUrl' => ['index']]);

        } else {
            return $this->render("create", ['model' => $model,'cancelUrl' => ['index']]);


        }

    }

    public function actionUpdate($officer_id,$company_id,$division_id, $postdict_id)
    {
        if (is_null($model = OfficerPost::findOne(compact('officer_id','company_id', 'division_id', 'postdict_id'))))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            return $this->goBack();
        };
        return $this->render("update", ['model' => $model,'cancelUrl' => ['index']]);
    }

    public function actionDelete($officer_id,$company_id,$division_id, $postdict_id)
    {
        if (is_null($model = OfficerPost::findOne(compact('officer_id','company_id', 'division_id', 'postdict_id'))))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };

        if ($model->delete()){

            if (\Yii::$app->request->isPjax) {
                // JSON response is expected in case of successful save
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['success' => true];
            }
            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }


    public function actionOfficerCreate()
    {


        $model = new Officer([

            'company_id' => \Yii::$app->base->company->primaryKey
        ]);
        $model->scenario = Officer::SCENARIO_LITE;

        if (\Yii::$app->request->post()){
            $model->load(\Yii::$app->request->post());
            $model->person->ident = new Ident();
            // $model->loadRelations(\Yii::$app->request->post());
            //   $validatedModel = $model->validate();
            if ( $model->save()){


                if (\Yii::$app->request->isAjax) {
                    // JSON response is expected in case of successful save
                    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return ['success' => true];
                }
                return $this->goBack();



            } else {
                foreach($model->getErrorSummary(true) as $errorStr){
                    \Yii::$app->session->setFlash("error",$errorStr);
                }

            }
        } else {
            $model->person = new Person();
            $model->person->ident = new Ident();
        }

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax("officer_create", ['model' => $model,'cancelUrl' => ['index']]);

        } else {
            return $this->render("officer_create", ['model' => $model,'cancelUrl' => ['index']]);

        }
    }
    public function actionOfficerView($id)
    {
        if (is_null($model = Officer::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t("ITEM_NOT_FOUND"));
        };
        $newOfficerPost = new OfficerPost([
            'officer_id' => $model->primaryKey,
            'company_id' => $model->company_id,
        ]);
        return $this->renderAjax('officer_view', ['model'=>$model, 'newOfficerPost' => $newOfficerPost]);
    }


}