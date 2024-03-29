<?php
namespace vova07\finances\models\backend;

use vova07\base\components\DateConvertJuiBehavior;
use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceAccounting;
use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\jobs\models\JobPaidList;
use vova07\jobs\Module;
use vova07\users\models\Prisoner;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:40 PM
 */

class BalanceByPrisonerWithCategoryViewSearch extends BalanceByPrisonerWithCategoryView
{

    public $only_debt = false;
    public $hasDevices;
    public $withoutJobs = false;
    public $from;
    public $to;
  //  public $sector_id;

    public function init()
    {

    }

    public function behaviors()
    {


        return [
            'fromJui' => [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'from',
                'juiAttribute' => 'fromJui'
            ],
            'toJui' => [
                'class' => DateConvertJuiBehavior::class,
                'attribute' => 'to',
                'juiAttribute' => 'toJui'
            ]
        ];

    }

    public function rules()
    {
        return [
            [['prisoner_id','only_debt','prisoner.sector_id','prisoner.status_id' ,'hasDevices', 'withoutJobs',
            'fromJui', 'toJui'
            ],'safe']
        ];
    }
    public function search($params)
    {

        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
        ]);
        $query->joinWith([
            'prisoner' => function($query) { $query->from([Prisoner::tableName()]);  },
            ]);

        $dataProvider->query->orderBy(['prisoner.sector_id' => SORT_ASC, 'fio'=>SORT_ASC]);

        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }

        $prisonerQuery = (new Query())->select('__person_id')->from(Prisoner::tableName());

            $prisonerQuery->andFilterWhere([
                'sector_id' => $this->getAttribute('prisoner.sector_id'),
                'status_id' => $this->getAttribute('prisoner.status_id')
            ]);


        $query->andWhere(
            ['prisoner_id' => $prisonerQuery]
        );

        $query->andFilterWhere(
            [
                'prisoner_id' => $this->prisoner_id,

            ]
        );
        if($this->only_debt==true){
            $query->andWhere(
               'remain<0'
            );
        }
        if ($this->hasDevices == true)
        {
            $query->andFilterWhere(
                ['in', 'prisoner_id', Device::find()->active_or_inactive()->select('prisoner_id')->distinct()]
            );
        }
        if ($this->withoutJobs == true)
        {
            $query->andFilterWhere(
                ['not in', 'prisoner_id', JobPaidList::find()->select('assigned_to')->distinct()]
            );
        }





        return $dataProvider;

    }

    public function attributes()
    {
        return array_merge(parent::attributes(),[
            'prisoner.sector_id',
            'prisoner.status_id'

        ]);
    }
    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'hasDevices' => Module::t('default','HAS_DEVICE_LABEL'),
                'withoutJobs' => Module::t('default','WITHOUT_JOBS_LABEL'),
                'fromJui' => Module::t('default','REMAIN_FROM_JUI_LABEL'),
                'toJui' => Module::t('default','REMAIN_TO_JUI_LABEL'),
            ]
        ) ;



    }




}