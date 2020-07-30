<?php
namespace vova07\psycho\models\backend;

use vova07\base\components\DateConvertJuiBehavior;
use vova07\prisons\models\Prison;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\models\PsyTest;
use vova07\psycho\models\PsyTestQuery;
use vova07\psycho\Module;
use vova07\users\models\Prisoner;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PrisonerTestSearch extends \vova07\users\models\backend\PrisonerViewSearch
{
    const HASTEST_WITH_TEST = 1;
    const HASTEST_WITHOUT_TEST = 2;
    const REFUSED_TEST = 9;

    public $hasTest ;
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),[
           [[ 'hasTest'],'integer']
        ]);
    }


    /**
     * @param $params
     * @param null $formName
     * @return \yii\data\ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function search($params, $formName = null)
    {
        $dataProvider = parent::search($params,$formName);
        if ($this->hasTest == self::HASTEST_WITH_TEST){
            $subQuery = PsyTest::find()->select('prisoner_id')->distinct();
            $dataProvider->query->andWhere(['__person_id' => $subQuery]);
        } elseif ($this->hasTest == self::HASTEST_WITHOUT_TEST){
            $subQuery = PsyTest::find()->select('prisoner_id')->distinct();
            $dataProvider->query->andWhere(['not in','__person_id', $subQuery]);
        } elseif ($this->hasTest == self::REFUSED_TEST){
            $subQuery = PsyTest::find()->select('prisoner_id')->andWhere(['status_id' => PsyTest::STATUS_ID_REFUSED])->distinct();
            $dataProvider->query->andWhere(['__person_id' => $subQuery]);
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

    public static function getHasTestFilter()
    {
        return [
            self::HASTEST_WITH_TEST => Module::t('default', 'HASTEST_WITH_TEST'),
            self::HASTEST_WITHOUT_TEST => Module::t('default', 'HASTEST_WITHOUT_TEST'),
            self::REFUSED_TEST => Module::t('default', 'REFUSED_TEST'),

        ];
    }
}