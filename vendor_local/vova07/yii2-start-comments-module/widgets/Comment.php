<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 2/25/20
 * Time: 11:04 AM
 */
namespace vova07\comments\widgets;

use http\Url;
use yii\base\Widget;
use yii\bootstrap\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ListView;

class Comment extends Widget
{
    public $model;

    public function run()
    {
        \Yii::$app->user->returnUrl = \yii\helpers\Url::current();
        $dataProvider = new ActiveDataProvider([
            'query' => $this->model->getComments(),
        ]);

        $box = \vova07\themes\adminlte2\widgets\Box::begin([
            'title' => 'comments',
        ]);
        $box->beginBody(['class'=>'box-comments']);

             echo ListView::widget([
            'dataProvider' => $dataProvider,
             'itemView' => '@vova07/comments/widgets/views/_commentItem'
        ]);


        $box->endBody();

        $box->beginFooter();
            $form = ActiveForm::begin([
                'action' => ['createComment']
            ]);
            $comment = new \vova07\comments\models\Comment();
            $comment->item_id = $this->model->item->primaryKey;
            echo $form->field($comment,'item_id')->hiddenInput()->label(false);
            echo $form->field($comment,'content');
            echo Html::submitButton();
            ActiveForm::end();
        $box->endFooter();

        \vova07\themes\adminlte2\widgets\Box::end();



    }
}


