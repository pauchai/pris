<?php
namespace vova07\users\controllers\backend;
use vova07\base\components\BackendController;
use vova07\prisons\models\Sector;
use vova07\users\models\backend\PrisonerLocationJournalWithNextViewSearch;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\users\models\backend\User;
use vova07\users\models\backend\UserSearch;
use vova07\users\models\Ident;
use vova07\users\models\Prisoner;
use vova07\users\models\PrisonerLocationJournal;
use vova07\users\models\PrisonerLocationJournalQuery;
use vova07\users\models\PrisonerLocationJournalView;
use vova07\users\models\PrisonerLocationJournalWithNextView;
use vova07\users\models\PrisonerView;
use yii\base\DynamicModel;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\NotFoundHttpException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class ReportController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['prisoners', 'location-journal', 'location-journal-init-for-current-year'],
                'roles' => ['@']
            ]
        ];
        return $behaviors;
    }

    public function actionPrisoners()
    {

        $searchModel = new PrisonerViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        if ($this->isPrintVersion)
            $dataProvider->pagination->pageSize = false;

        //   return $this->render("index_print", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);
        //} else
        return $this->render("index", ['dataProvider'=>$dataProvider,'searchModel' => $searchModel]);

    }

    public function actionLocationJournal()
    {

        $searchModel = new PrisonerLocationJournalWithNextViewSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        $prisonerDataProvider = new ActiveDataProvider();
        $prisonerDataProvider->query = Prisoner::find()->joinWith('person')->orderBy('person.second_name asc');
        $prisonerDataProvider->pagination = false;
        if ($searchModel->sector_id){
            $prisonerDataProvider->query->notDeleted()->andWhere(['sector_id' => $searchModel->sector_id]);
        } else {
            $prisonerDataProvider->query->where(0);
        }





        $dataProvider->pagination = false;
        \Yii::$app->user->setReturnUrl(\Yii::$app->request->getAbsoluteUrl());

        return $this->render('location_journal', ['dataProvider' => $dataProvider,'searchModel' => $searchModel, 'prisonerDataProvider' => $prisonerDataProvider]);
    }

    public function actionLocationJournalInitForCurrentYear()
    {
        if (\Yii::$app->request->isPost)
        {
            PrisonerLocationJournal::initForCurrentYear();
        }
        return $this->goBack();
    }

}