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
use vova07\reports\controllers\backend\PrisonerProgramController;
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
use yii\db\QueryBuilder;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

class ReportPrisonerProgramPlannedSearch extends ProgramPrisoner
{
    public $year;
    public $sector_id;


    public function rules()
    {
        return [

            [['year'],'safe'],
            [['sector_id'],'required'],


        ];
    }
    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()->andWhere(['program_prisoners.status_id' => ProgramPrisoner::STATUS_PLANED])
            ->andWhere(['prisoner.status_id' => ProgramPrisoner::STATUS_ACTIVE])
        ]);
        $dataProvider->query
            ->joinWith(['person' => function($query){$query->from('person');}])
            ->joinWith(['prisoner' => function($query){$query->from('prisoner');}])
            //->joinWith(['ownableitem' => function($query){$query->from('ownableitem');}])
            ->orderBy('prison_id, person.second_name, person.first_name, prisoner.sector_id, programdict_id,');
       ;

        $this->load($params);
        if ( $this->validate()){

            $dataProvider->query->andWhere([
                'sector_id' => $this->sector_id,

            ]);
            $dataProvider->query->andFilterWhere([
                'date_plan' => $this->year,

            ]);

        } else {
            $dataProvider->query->andWhere('false');
        }

        return $dataProvider;

    }



}