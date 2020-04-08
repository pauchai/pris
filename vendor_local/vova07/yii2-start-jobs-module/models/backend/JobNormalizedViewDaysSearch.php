<?php
namespace vova07\jobs\models\backend;



use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\jobs\models\JobNormalizedViewDays;

class JobNormalizedViewDaysSearch extends JobNormalizedViewDays
{
    public $atFrom;
    public $atTo;

    public function rules()
    {
        return [
            [['atFromJui', 'atToJui'],'safe']
        ];
    }

    public function behaviors()
    {
        return [
            'dateIssueJui'=>[
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'atFrom',
                'juiAttribute' => 'atFromJui'
            ],
            'dateExpirationJui'=>[
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'atTo',
                'juiAttribute' => 'atToJui',
                //  'dateFormat' => 'dd-mm-yyyy'
            ]
        ];
    }
    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        if ($this->load($params) && $this->validate()){
            $dataProvider->query->andFilterWhere(
                ['>=', 'at', $this->atFrom  ]
            );
            $dataProvider->query->andFilterWhere(
                ['<=', 'at', $this->atTo  ]
            );
        }
        return $dataProvider;

    }

}