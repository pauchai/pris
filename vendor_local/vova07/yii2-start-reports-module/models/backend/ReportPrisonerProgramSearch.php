<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/6/20
 * Time: 2:11 PM
 */

namespace vova07\reports\models\backend;


use vova07\concepts\models\Concept;
use vova07\concepts\models\ConceptClass;
use vova07\concepts\models\ConceptParticipant;
use vova07\concepts\models\ConceptVisit;
use vova07\events\models\Event;
use vova07\events\models\EventParticipant;
use vova07\jobs\models\JobNormalizedViewDays;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\ProgramVisit;
use vova07\prisons\models\Sector;
use vova07\tasks\models\Committee;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use vova07\users\models\PrisonerLocationJournalView;
use vova07\users\models\PrisonerView;
use vova07\users\models\PrisonerViewQuery;
use yii\base\Model;
use vova07\base\components\DateJuiBehavior;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\jui\DatePicker;

class ReportPrisonerProgramSearch extends PrisonerView
{
    public $year;
    public $sector_id;

    public function rules()
    {
        return [
            [['year', 'sector_id'],'safe'],


        ];
    }
    public function getSector()
    {
        return Sector::findOne($this->sector_id);
    }
    public function search($params)
    {
        $query = self::find()->active()->orderBy('fio');
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query

        ]);

        if ($this->load($params) && $this->validate()){
            $query->andFilterWhere([
                'sector_id' => $this->sector_id,

            ]);
        }
        return $dataProvider;

    }

}