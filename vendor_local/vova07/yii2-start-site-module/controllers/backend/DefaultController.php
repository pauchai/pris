<?php
namespace vova07\site\controllers\backend;


use vova07\users\models\LoginForm;
use vova07\users\models\User;
use vova07\users\Module;
use yii\web\UnauthorizedHttpException;

class DefaultController extends \yii\web\Controller
{

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::className()
            ],
        ];
    }

    public function actionLogin()
    {
        $this->layout = '@vova07/themes/adminlte2/views/layouts/blank';
        if (!\Yii::$app->user->isGuest) {
            $this->redirect(['login-info']);
        }
            $model = new LoginForm();
            if ($model->load(\Yii::$app->request->post())){
                if ($model->validate()){
                    if ($model->login())
                    {
                        //print_r($_SESSION);
                        //phpinfo();
                        //exit;
                        $this->redirect(['dash-board/index']);
                    }
                }
            }

        return $this->render("login_form",['model' => $model]);


    }

    public function actionLoginInfo()
    {
        $this->layout = '@vova07/themes/adminlte2/views/layouts/blank';
        if (\Yii::$app->user->isGuest){
              $this->redirect(['login']);
        } else {
            $model = \Yii::$app->user->getIdentity();
            return $this->render("login_info", ['model' => $model]);
        }

    }

    public function actionTestRedirectLogin()
    {
        $this->redirect(['login']);
    }

    public function actionLogout()
    {

        \Yii::$app->user->logout();
        $this->redirect(['login']);

    }

    public function actionMaintenance()
    {
        $this->layout = '@vova07/themes/adminlte2/views/layouts/blank';
        return $this->render('maintenance');
    }


    public function actionSwitchUser($id)
    {
          $backUrl = \Yii::$app->request->referrer;
            if (\Yii::$app->session->get(Module::QUICK_SWITCH_USER_ENABLED_SESSION_PARAM_NAME)){
                $user = User::findOne($id);
                \Yii::$app->user->switchIdentity($user);
            } else {
                throw new UnauthorizedHttpException();
            }

            return $this->redirect($backUrl);
    }

}