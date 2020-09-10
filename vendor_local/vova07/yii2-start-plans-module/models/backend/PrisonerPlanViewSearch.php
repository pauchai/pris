<?php
namespace vova07\plans\models\backend;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateJuiBehavior;
use yii\db\Expression;
use vova07\plans\models\PrisonerPlanView;
use vova07\plans\models\Program;
use vova07\users\models\Prisoner;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class PrisonerPlanViewSearch extends PrisonerPlanView
{

    public $assignedAtFrom, $assignedAtTo;

    public function rules()
    {
        return [
            [['prisoner.status_id', 'status_id'],'integer'],
           // [['prisoner.status_id'],'default','value' => Prisoner::STATUS_ACTIVE],
            [['assignedAtFromJui', 'assignedAtToJui'],'date']
        ];
    }
    public function search($params)
    {

        $query = self::find()->joinWith('prisoner');
       // $query->andWhere([  'prisoner_status_id' => Prisoner::STATUS_ACTIVE]);
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        if ($this->load($params) && $this->validate()){
            if ($this->status_id == self::STATUS_ABSENT)
                $query->andWhere(new Expression('ISNULL(status_id)'));
            else
            $query->andFilterWhere(
                [
                    'vw_prisoner_plan.status_id' => $this->status_id,
                    'prisoner.status_id' => $this->getAttribute('prisoner.status_id'),
                ]
            );
            if ($this->assignedAtTo || $this->assignedAtFrom){
                $query->andFilterWhere(['>=', 'assigned_at',$this->assignedAtFrom])
                    ->andFilterWhere(['<=', 'assigned_at', $this->assignedAtTo ])
                    ->andWhere(new Expression('NOT ISNULL(assigned_to)'));
            }

        };






        return $dataProvider;

    }

    public function behaviors()
    {
        $ret = ArrayHelper::merge(
            parent::behaviors(),
            [
                'assignedAtFromJui'=>[
                    'class' => DateJuiBehavior::class,
                    'attribute' => 'assignedAtFrom',
                    'juiAttribute' => 'assignedAtFromJui'
                ],
                'assignedAtToJui'=>[
                    'class' => DateJuiBehavior::class,
                    'attribute' => 'assignedAtTo',
                    'juiAttribute' => 'assignedAtToJui'
                ],
            ]
        );
        return $ret;
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), ["prisoner.status_id"]);
    }
}