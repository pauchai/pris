<?php
namespace vova07\users\models\backend;

use vova07\base\components\DateConvertJuiBehavior;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PrisonerSearch extends \vova07\users\models\PrisonerView
{

    public function rules()
    {
        return [
            [['article'],'string'],
            [['termStartJui','termFinishJui','termUdoJui'],'date'],
            [['__person_id','prison_id', 'status_id','sector_id','fio'],'safe'],
            [['status_id'],'default','value' => Prisoner::STATUS_ACTIVE]
        ];
    }

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()
        ]);
        $dataProvider->query->orderBy(['fio'=>SORT_ASC]);
        //$dataProvider->query->active();

        $this->load($params);
        $this->validate();
            $dataProvider->query->andFilterWhere(
                [
                    'prison_id' => $this->prison_id,
                    'sector_id' => $this->sector_id,
                    '__person_id' => $this->__person_id,
                    'status_id' => $this->status_id,
                    'term_start' => $this->term_start,
                    'term_finish' => $this->term_finish,
                    'term_udo' => $this->term_udo

                ]);

        return $dataProvider;

    }


}