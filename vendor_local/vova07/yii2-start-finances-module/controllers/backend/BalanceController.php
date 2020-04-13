<?php
namespace vova07\finances\controllers\backend;
use kartik\mpdf\Pdf;
use vova07\base\components\BackendController;
use vova07\base\tools\PrinceXml\Prince;
use vova07\finances\models\backend\BalanceByPrisonerViewSearch;
use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\backend\BalanceByPrisonerWithCategoryViewSearch;
use vova07\finances\models\backend\BalanceSearch;
use vova07\finances\models\Balance;
use vova07\rbac\Module;
use yii\data\SqlDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/26/19
 * Time: 3:22 PM
 */

class BalanceController extends BackendController
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['access']['rules'] = [
            [
                'allow' => true,
                'actions' => ['index','print-receipt','delete', 'print-archive'],
                'roles' => [Module::PERMISSION_FINANCES_ACCESS]
            ]
        ];
        return $behaviors;
    }

    public function actionIndex()
    {
        $searchModel = new BalanceSearch();

        $newModel = new Balance();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());
        \Yii::$app->user->returnUrl = Url::current();
        if ($newModel->load(\Yii::$app->request->post())) {
            $newModel->save();
        }


        $newModel->attributes = $searchModel->getAttributes(['prisoner_id', 'type_id', 'category_id', 'reason', 'atJui']);

        return $this->render("index", ['dataProvider' => $dataProvider, 'searchModel' => $searchModel, 'newModel' => $newModel]);
    }

    public function actionPrintReceipt()
    {
        $searchModel = new BalanceSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->get());

        //\Yii::$app->response->isSent = true;
       // $contentHtml = $this->render("print_receipt", ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);
        $princePdf = new Prince('/usr/bin/prince');
      //  header('Content-Type: application/pdf');
       // header('Content-Disposition: inline; filename="foo.pdf"');
        $arr  = [];

        //$princePdf->convert_string_to_passthru($contentHtml, $arr);

        return $this->render("print_receipt", ['dataProvider' => $dataProvider, 'searchModel' => $searchModel]);




    }

    public function actionPrintArchive()
    {
        $query =  (new Query())->select(
            [
                'b1.prisoner_id',
                'debit' => new \yii\db\Expression('ifnull(CASE WHEN b1.type_id=' . Balance::TYPE_DEBIT. ' THEN b1.amount END, 0)'),
                'credit' => new \yii\db\Expression('ifnull(CASE WHEN b1.type_id=' . Balance::TYPE_CREDIT. ' THEN b1.amount END, 0)'),
                'b1.at',
                'b1.reason',
                'fio' => new Expression("concat(person.second_name, ' ' , person.first_name , ' ', person.patronymic, ' ' , person.birth_year )"),


            ]
        )->leftJoin('person','person.__ident_id = b1.prisoner_id')
            ->leftJoin('prisoner','prisoner.__person_id = b1.prisoner_id')
        ->from(['b1' => Balance::tableName()])
            ->orderBy('person.second_name, person.first_name, person.patronymic, person.birth_year, b1.at')
        ->andFilterWhere([
            'prisoner.status_id' => \Yii::$app->request->get('prisoner_status_id'),
            'prisoner_id' => \Yii::$app->request->get('prisoner_id')
        ]);



        list($sql, $params) = \Yii::$app->db->getQueryBuilder()->build($query);
         $dataProvider = new SqlDataProvider([
            'sql' => $sql,
            'params' => $params,
        ]);
         //$dataProvider->pagination = false;


         return $this->render('print-archive', ['dataProvider' => $dataProvider]);
    }

    public function actionDelete($id)
    {
        if (is_null($model = Balance::findOne($id)))
        {
            throw new NotFoundHttpException(Module::t('default',"ITEM_NOT_FOUND"));
        };
        if ($model->delete()){
            return $this->goBack();
        };
        throw new \LogicException(Module::t('default',"CANT_DELETE"));
    }

}