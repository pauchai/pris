<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 1/31/20
 * Time: 11:23 AM
 */
namespace vova07\psycho\models\backend;


use vova07\base\components\DateConvertJuiBehavior;
use vova07\psycho\models\PsyTest;
use yii\helpers\ArrayHelper;

class PsyTestSearch extends PsyTest
{
    public $atFrom;
    public $atTo;
    public function rules()
    {
        return [
            [['atFrom', 'atTo','atFromJui','atToJui'], 'safe'],
        ];
    }

    public  function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(),[
            [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'atFrom',
                'juiAttribute' => 'atFromJui'

            ],
            [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'atTo',
                'juiAttribute' => 'atToJui'

            ]
        ]);
        return $behaviors;
    }

    public function search($params, $formName = null)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);

        $this->load($params, $formName);
        $this->validate();


        return $dataProvider;

    }

}