<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/28/19
 * Time: 9:38 AM
 */

namespace vova07\videos\commands;


use vova07\videos\models\Word;
use yii\console\Controller;

class WordsController extends Controller
{

    public $translateCli = "trans en:ru -b {{%WORD}}";

    public function actionTranslate()
    {

        $models = Word::find()->where("translation is NULL")->all();
        foreach ($models as $model)
        {
            $translation = system(strtr($this->translateCli, ["{{%WORD}}" => $model->title]));
            if (!($translation === false)){
                $model->translation = $translation;
                $model->save();
            } else {
                throw new \LogicException("Ошибка в переводе");
            }

        }

    }

}