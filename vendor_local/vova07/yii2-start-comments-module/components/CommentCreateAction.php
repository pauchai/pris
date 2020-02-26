<?php
namespace vova07\comments\components;
use vova07\comments\models\Comment;
use vova07\comments\Module;
use yii\base\Action;
use YoutubeDl\Exception\NotFoundException;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 2/25/20
 * Time: 1:08 PM
 */

class CommentCreateAction extends Action
{
    public function run()
    {
        if (\Yii::$app->request->isPost)
        {
            $comment = new Comment();
            if ($comment->load(\Yii::$app->request->post()) && $comment->validate()){
                if (!$comment->save(false)) {
                    throw new \LogicException($comment->getFirstError());
                } else {
                    return $this->controller->goBack();
                };



            } else {
                throw new NotFoundException(Module::t('default',"ITEM_NOT_FOUND"));

            }
        }
    }
}