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
use yii\db\Query;
use yii\helpers\Html;
use yii\widgets\ListView;

class CommentCountWithPopover extends Widget
{
    /**
     * @var $query Query
     */
    public $query;

    public function run()
    {


        if ($this->query->count()) {
            $commentsText = '';
            foreach ($this->query->all() as $comment){
                $commentsText .= $comment->content . "\n";
            }
            echo  Html::tag('span', '', ['class' => 'fa fa-comment', 'title'=>$commentsText, 'data'=>['toggle' => 'tooltip', 'content' => 'test']]);
        }

        $this->view->registerJs(<<<JSS
        $(".fa-comment a").popover({
            placement : 'top'
        });
JSS
);

    }
}


