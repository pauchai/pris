<?php
namespace vova07\humanitarians\controllers\backend;
use vova07\base\components\BackendController;
use vova07\events\models\Event;
use vova07\events\Module;
use vova07\humanitarians\models\backend\HumanitarianByPrisonerPivotSearch;
use vova07\humanitarians\models\backend\HumanitarianIssueSearch;
use vova07\humanitarians\models\backend\HumanitarianPrisonerSearch;
use vova07\humanitarians\models\HumanitarianIssue;
use vova07\humanitarians\models\HumanitarianItem;
use vova07\humanitarians\models\HumanitarianPrisoner;
use vova07\tasks\models\backend\CommitteeSearch;
use vova07\tasks\models\Committee;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\users\models\backend\User;
use vova07\users\models\Officer;
use vova07\users\models\Prisoner;
use yii\base\Exception;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class IssuesController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index'],
                'roles' => [\vova07\rbac\Module::PERMISSION_HUMANITARIAN_LIST]
            ],
            [
                'allow' => true,
                'actions' => ['create'],
                'roles' => [\vova07\rbac\Module::PERMISSION_HUMANITARIAN_CREATE]
            ],
            [
                'allow' => true,
                'actions' => ['update'],
                'roles' => [\vova07\rbac\Module::PERMISSION_HUMANITARIAN_UPDATE]
            ],
            [
                'allow' => true,
                'actions' => ['delete'],
                'roles' => [\vova07\rbac\Module::PERMISSION_HUMANITARIAN_DELETE]
            ],
            [
                'allow' => true,
                'actions' => ['view', 'view-print', 'view-for-managment'],
                'roles' => [\vova07\rbac\Module::PERMISSION_HUMANITARIAN_VIEW]
            ],
            [
                'allow' => true,
                'actions' => ['toggle-item'],
                'roles' => ['@']
            ],
            [
                'allow' => true,
                'actions' => ['mass-add'],
                'roles' => ['@']
            ],
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new HumanitarianIssueSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        \Yii::$app->user->returnUrl = Url::current();
        return $this->render("index", ['dataProvider' => $dataProvider]);
    }

    public function actionCreate()
    {

        $model = new HumanitarianIssue();
        $model->validate();

        if (\Yii::$app->request->post()) {
            $model->load(\Yii::$app->request->post());
            if ($model->validate() && $model->save()) {
                $redirectUrl = ['id' => $model->primaryKey];
                $redirectUrl[0] = 'view';
                return $this->goBack();
            } else {
                \Yii::$app->session->setFlash('error', join("<br/>", $model->getFirstErrors()));
            }
        }
        return $this->render("create", ['model' => $model]);
    }

    public function actionView($id)
    {
        if (is_null($model = HumanitarianIssue::findOne($id))) {
            throw new NotFoundHttpException(Module::t('default', "ITEM_NOT_FOUND"));
        };

            if ($model->status_id == HumanitarianIssue::STATUS_PROCESSING)
                $this->redirect(['view-for-managment', 'id'=>$id]);


            $searchModel = new HumanitarianByPrisonerPivotSearch();
            $searchModel->issue_id = $id;
            $dataProvider =  $searchModel->search(\Yii::$app->request->get());
            $dataProvider->query->andWhere(['pr.status_id' => Prisoner::STATUS_ACTIVE]);



            $dataProvider->pagination->setPageSize(100);


            //$dataProvider->pagination=false;

        if ($this->isPrintVersion){
            $director = User::findOne(['role' => \vova07\rbac\Module::ROLE_COMPANY_HEAD])->officer;
            $officer = \Yii::$app->user->identity->officer;
            $dataProvider->pagination = false;

            if (!$officer){
                throw new Exception('Only for officers');
            }
            return $this->render('view-print', compact('model', 'searchModel', 'dataProvider', 'officer', 'director'));
        } else{
            return $this->render('view', compact('model', 'searchModel', 'dataProvider'));
        }





    }

    public function actionViewForManagment($id)
    {
        if (is_null($model = HumanitarianIssue::findOne($id))) {
            throw new NotFoundHttpException(Module::t('default', "ITEM_NOT_FOUND"));
        };
        $prisonerSearch = new PrisonerViewSearch();
        $dataProvider = $prisonerSearch->search(\Yii::$app->request->get());
        $dataProvider->pagination->setPageSize(100);
        //$dataProvider->pagination=false;


        return $this->render('view-for-managment', ['model' => $model, 'dataProvider' => $dataProvider, 'searchModel' => $prisonerSearch]);



    }


    public function actionDelete($id)
    {
        if (is_null($model = HumanitarianIssue::findOne($id))) {
            throw new NotFoundHttpException(Module::t('default', "ITEM_NOT_FOUND"));
        };
        if ($model->delete()) {
            return $this->goBack();
        };
        throw new \LogicException(Module::t('default', "CANT_DELETE"));
    }

    public function actionUpdate($id)
    {
        if (is_null($model = HumanitarianIssue::findOne($id))) {
            throw new NotFoundHttpException(Module::t('default', "ITEM_NOT_FOUND"));
        };

        if (\Yii::$app->request->isPost) {
            $model->load(\Yii::$app->request->post());
            if ($model->validate()) {
                if ($model->save()) {
                    return $this->goBack();
                };
            };
        }

        return $this->render("update", ['model' => $model, 'cancelUrl' => ['index']]);
    }

    public function actionToggleItem($issue_id, $item_id, $prisoner_id)
    {
        $idArr = compact('issue_id', 'item_id', 'prisoner_id');
        if (is_null($model = HumanitarianPrisoner::findOne($idArr))) {
            $model = new HumanitarianPrisoner();
            if ($model->load($idArr, '')) {
                $model->validate();
                $model->save();
                $response = array(
                    'status' => 'success',
                    'action' => 'insert',
                    'message' => Module::t('default', 'SAVED_SUCCESSFULL!'),
                );
            };

        } else {
            $model->delete();
            $response = array(
                'status' => 'success',
                'action' => 'delete',
                'message' => Module::t('default', 'DELETED_SUCCESSFULL!'),
            );
        }


        \Yii::$app->getResponse()->format = \yii\web\Response::FORMAT_JSON;


        return $response;
    }

    public function actionMassAdd($issue_id, $item_id, $sector_id)
    {
        $prisonerSearch = new PrisonerViewSearch();
        $dataProvider = $prisonerSearch->search(\Yii::$app->request->get());
        $prisons = Prisoner::findAll(['sector_id' => $sector_id]);

        foreach ($prisons as $prisoner)
        {
            $prisoner_id = $prisoner->primaryKey;
            $humanitarianPrisoner = HumanitarianPrisoner::findOne(compact(['issue_id','item_id','prisoner_id']));
            if (!$humanitarianPrisoner){

                $humanitarianPrisoner = new HumanitarianPrisoner();
                $humanitarianPrisoner->setAttributes(compact(['issue_id','item_id','prisoner_id']));
                $humanitarianPrisoner->save();

            } else {
                $humanitarianPrisoner->delete();
            }

        }
       // return $this->goBack();

       $response =  \Yii::$app->getResponse()->format = \yii\web\Response::FORMAT_JSON;


        return $response;
    }

}