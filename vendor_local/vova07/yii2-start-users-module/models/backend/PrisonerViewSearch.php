<?php
namespace vova07\users\models\backend;

use vova07\base\components\DateConvertJuiBehavior;
use vova07\prisons\models\Prison;
use vova07\psycho\models\PsyCharacteristic;
use vova07\users\models\Prisoner;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PrisonerViewSearch extends \vova07\users\models\PrisonerView
{
    public $termStartFrom, $termStartTo;
    public $termFinishFrom, $termFinishTo;
    public $termUdoFrom, $termUdoTo;
    public $enabledSaveSearch = false;
    public $birth_year_from;
    public $birth_year_to;

    /**
     * @var boolean
     */
    public $hasPsycho = false;

    public function rules()
    {
        return [
            [['article'],'string'],
            [['termStartJui','termFinishJui','termUdoJui'],'date'],
            [['termStartFromJui','termStartToJui','termFinishFromJui','termFinishToJui','termUdoFromJui','termUdoToJui'],'date'],
            [['__person_id','prison_id', 'status_id','sector_id','fio'],'safe'],
            [['status_id'],'default','value' => Prisoner::STATUS_ACTIVE],
            [['enabledSaveSearch', 'hasPsycho'],'boolean'],
            [['birth_year_from', 'birth_year_to'], 'number']
        ];
    }
    public function searchFromSession()
    {
        $params = \Yii::$app->session->get($this->formName());
        return $this->search($params, '');
    }

    /**
     * @param $params
     * @param null $formName
     * @return \yii\data\ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params, $formName = null)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        $dataProvider->query->orderBy(['fio'=>SORT_ASC]);
        //$dataProvider->query->active();

        $this->load($params, $formName);
        if ($this->enabledSaveSearch){
            if ($params[$this->formName()]['enabledSaveSearch'])
                unset($params[$this->formName()]['enabledSaveSearch']);
            \Yii::$app->session->set($this->formName(),$params[$this->formName()]);
        }
        $this->validate();
            $dataProvider->query->andFilterWhere(
                [
                    'prison_id' => $this->prison_id,
                    'sector_id' => $this->sector_id,
                    '__person_id' => $this->__person_id,
                    'status_id' => $this->status_id,


                ]);

        $dataProvider->query->andFilterWhere(['like','article', $this->article ]);


        $dataProvider->query->andFilterWhere(['>=','term_start',$this->termStartFrom])
            ->andFilterWhere(['<=','term_start',$this->termStartTo]);

        $dataProvider->query->andFilterWhere(['>=','term_finish',$this->termFinishFrom])
            ->andFilterWhere(['<=','term_finish',$this->termFinishTo]);

        $dataProvider->query->andFilterWhere(['>=','term_udo',$this->termUdoFrom])
            ->andFilterWhere(['<=','term_udo',$this->termUdoTo]);

        $dataProvider->query->andFilterWhere(['>=', 'birth_year', $this->birth_year_from]);
        $dataProvider->query->andFilterWhere(['<=', 'birth_year', $this->birth_year_to]);

        if ($this->hasPsycho)
        {
            $dataProvider->query->joinWith(['characteristic' => function($query) { $query->from(['characteristic' => 'psy_characteristics']); }]);
            $dataProvider->query->andWhere(
                [
                    'or',
                    new Expression('feature_violent=1'),
                    new Expression('feature_self_torture=1'),
                    new Expression('feature_sucide=1'),
                    new Expression('feature_addiction_alcohol=1'),
                    new Expression('feature_addiction_drug=1'),
                ]
            );
        }

        return $dataProvider;

    }

    public function behaviors()
    {
        $add =
            [
            'termStartFromJui'=>[
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'termStartFrom',
                'juiAttribute' => 'termStartFromJui'
            ],
            'termStartToJui'=>[
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'termStartTo',
                'juiAttribute' => 'termStartToJui'
            ],
                'termFinishFromJui'=>[
                    'class' => DateConvertJuiBehavior::class,
                    'attribute' => 'termFinishFrom',
                    'juiAttribute' => 'termFinishFromJui'
                ],
                'termFinishToJui'=>[
                    'class' => DateConvertJuiBehavior::class,
                    'attribute' => 'termFinishTo',
                    'juiAttribute' => 'termFinishToJui'
                ],
                'termUdoFromJui'=>[
                    'class' => DateConvertJuiBehavior::class,
                    'attribute' => 'termUdoFrom',
                    'juiAttribute' => 'termUdoFromJui'
                ],
                'termUdoToJui'=>[
                    'class' => DateConvertJuiBehavior::class,
                    'attribute' => 'termUdoTo',
                    'juiAttribute' => 'termUdoToJui'
                ],
            ];

        return ArrayHelper::merge(parent::behaviors(),$add);
    }

}