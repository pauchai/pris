<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models\backend;




use vova07\base\components\DateConvertJuiBehavior;
use vova07\plans\components\SummarizedDataProvider;
use vova07\plans\models\SummarizedModel;
use yii\base\Model;
use yii\helpers\ArrayHelper;


class SummarizedSearch extends  SummarizedModel
{

    public $at_from;
    public $at_to;
    public function rules()
    {
        return [
            [['atFromJui','atToJui'],'string']
        ];
    }
    public function behaviors()
    {
        $behaviors = [
            [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'at_from',
                'juiAttribute' => 'atFromJui'

            ],
            [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'at_to',
                'juiAttribute' => 'atToJui'

            ]
        ];
        return $behaviors;
    }

    public function search($params)
    {
        $dataProvider = new SummarizedDataProvider;
        if ($this->load($params) && $this->validate()){
            $dataProvider->from = $this->at_from;
            $dataProvider->to = $this->at_to;
        }
        return $dataProvider;
    }

}
