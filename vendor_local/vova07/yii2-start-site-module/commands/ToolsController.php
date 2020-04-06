<?php
namespace vova07\site\commands;

use vova07\concepts\models\ConceptParticipant;
use vova07\users\models\User;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/30/19
 * Time: 5:34 PM
 */

class ToolsController extends \yii\console\Controller
{
    public function actionRemoveUnusedConcepts()
    {
       $this->doLogin();
       foreach (ConceptParticipant::find()->all() as $conceptParticipant)
       {
           if (!$conceptParticipant->concept)
               $conceptParticipant->delete();
       };

    }



    private function doLogin()
    {

        $user = User::findOne(['username' => 'admin']);
        $ident = $user->ident;
        \Yii::$app->user->setIdentity($ident);
    }



}

