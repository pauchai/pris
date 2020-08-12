<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/6/20
 * Time: 2:11 PM
 */

namespace vova07\prisons\models\backend;


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
use yii\base\Model;
use vova07\base\components\DateJuiBehavior;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\jui\DatePicker;

class ReportSummarizedSearch extends Model
{
    public $from;
    public $to;
    public $sector_id;

    public function rules()
    {
        return [
            [['from', 'to', 'fromJui','toJui', 'sector_id'],'safe'],


        ];
    }
    public function behaviors()
    {
        return [
            'fromJui' => [
                'class' => DateJuiBehavior::class,
                'attribute' => 'from',
                'juiAttribute' => 'fromJui',

            ],
            'toJui' => [
                'class' => DateJuiBehavior::class,
                'attribute' => 'to',
                'juiAttribute' => 'toJui',

            ],
        ];
    }
    public function getSector()
    {
        return Sector::findOne($this->sector_id);
    }
    public function applyFilter()
    {
        $this->load(\Yii::$app->request->get());
        $this->validate();
    }

    private function getBaseLocationQuery()
    {
        $query = (new Query())
            ->from(PrisonerLocationJournalView::tableName() . ' j')
            ->leftJoin(PrisonerLocationJournalView::tableName() .' jprev', 'j.prev_id = jprev.id')
            ->leftJoin(Prisoner::tableName() .' pr', 'pr.__person_id = j.prisoner_id')
            ->leftJoin(Sector::tableName() .' s', 'j.sector_id = s.__ownableitem_id');


        if ($this->from)
            $query->andFilterWhere(['>=', 'j.at', $this->from]);

        if ($this->to)
            $query->andFilterWhere(['<=', 'j.at', $this->to]);

      //  $query->andFilterWhere([
       //     'pr.sector_id' => $this->sector_id
       // ]);
        return $query;
    }

    public function getFromSectorQuery()
    {
        return ( $this->getBaseLocationQuery())
            ->andWhere(['<>', 'jprev.sector_id', new Expression('j.sector_id')])
            ->andWhere(['jprev.sector_id' => $this->sector_id]);

    }

    public function getToSectorQuery()
    {
        return ( $this->getBaseLocationQuery())
            ->andWhere(['or', 'jprev.sector_id =  j.sector_id',new Expression("ISNULL(j.prev_id)") ])

        ->andWhere([
        'j.sector_id' => $this->sector_id
    ]);


    }

    public function getToPrisonQuery()
    {
        return ( $this->getBaseLocationQuery())->andWhere(new Expression("ISNULL(j.prev_id)"))
            ->andWhere([
                'j.sector_id' => $this->sector_id

            ]);
    }
    public function getFromPrisonQuery()
    {
        return ( $this->getBaseLocationQuery())->andWhere(['j.status_id' => Prisoner::STATUS_ETAP]);
    }
    public function getFromToLocationProvider()
    {



        $dataProvider = new  ArrayDataProvider([
            'allModels' => [
                [
                    'fromSectorCount' => $this->getFromSectorQuery()->count(),
                    'toSectorCount' => $this->getToSectorQuery()->count(),
                    'fromPrisonCount' => $this->getFromPrisonQuery()->count(),
                    'toPrisonCount' => $this->getToPrisonQuery()->count(),


                ]
            ]

            ]);
        return $dataProvider;
    }

    public function getTerminateQuery($status_id = null)
    {
        return $this->getBaseLocationQuery()
            ->andWhere(['j.status_id' => Prisoner::getTermStatuses()])
            ->andFilterWhere(['j.sector_id' => $this->sector_id])
            ->andFilterWhere(['j.status_id' => $status_id]);

    }

    public function getTerminateProvider()
    {
        $query = $this->getTerminateQuery()
            ->select([
                'j.status_id',
                'prisoners_count' => 'count(DISTINCT j.prisoner_id)'
            ])->groupBy(['j.status_id']);


       $dataProvider = new ActiveDataProvider(['query' => $query]);

        return $dataProvider;
    }


    public function getProgramsQuery($program_id = null)
    {
        $query = (new Query())
            ->from(ProgramVisit::tableName() . ' pv')
            ->leftJoin(ProgramPrisoner::tableName() .' pp', 'pp.__ownableitem_id = pv.program_prisoner_id')
            ->leftJoin(ProgramDict::tableName() .' pd', 'pp.programdict_id = pd.__ownableitem_id')
            ->leftJoin(Prisoner::tableName() .' pr', 'pr.__person_id = pp.prisoner_id');

        //->groupBy(['pv.prison_id', 'pp.programdict_id'])
        if ($this->from)
            $query->andFilterWhere(['>=', 'date_visit', (new \DateTime())->setTimestamp($this->from)->format('Y-m-d')]);

        if ($this->to)
            $query->andFilterWhere(['<=', 'date_visit', (new \DateTime())->setTimestamp($this->to)->format('Y-m-d')]);

        $query->andFilterWhere([
            'pr.sector_id' => $this->sector_id
        ]);
        $query->andFilterWhere([
           'pd.__ownableitem_id' => $program_id
        ]);

        return $query;
    }
    public function getProgramsProvider()
    {
        $query = $this->getProgramsQuery()
            ->select([
                'programdict_id' => 'pd.__ownableitem_id',
                'program_title' => 'pd.title',


                'count_prisoners' => 'count(DISTINCT pp.prisoner_id)'

            ])->groupBy(['pd.__ownableitem_id']);

        $dataProvider = new ActiveDataProvider([
           'query' => $query

        ]);

        return $dataProvider;
    }

    public function getConceptsQuery($concept_id = null)
    {
        $query = (new Query())
            ->from(ConceptVisit::tableName() . ' cv')
            ->leftJoin(ConceptClass::tableName() . ' cc', 'cv.class_id = cc.__ownableitem_id')
            ->leftJoin(ConceptParticipant::tableName() . ' cp', 'cv.participant_id = cp.__ownableitem_id')
            ->leftJoin(Concept::tableName() . ' c', 'cp.concept_id = c.__ownableitem_id')
            ->leftJoin(Prisoner::tableName() .' pr', 'pr.__person_id = cp.prisoner_id');


        if ($this->from)
            $query->andFilterWhere(['>=', 'cc.at', $this->from]);

        if ($this->to)
            $query->andFilterWhere(['<=', 'cc.at', $this->to]);

        $query->andFilterWhere([
            'pr.sector_id' => $this->sector_id
        ]);

        $query->andFilterWhere([
            'cp.concept_id' => $concept_id
        ]);
        return $query;
    }
    public function getConceptsProvider()
    {
        $query = $this->getConceptsQuery()
            ->groupBy(['cp.concept_id'])
            ->select([
                'cp.concept_id',
                'c.title',
                'participants_count' => 'count(DISTINCT cp.prisoner_id)'
            ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);

        return $dataProvider;
    }

    public function getEventsQuery($event_id = null)
    {
        $query = (new Query())
            ->from(EventParticipant::tableName() . ' ep')
            ->leftJoin(Event::tableName() . ' e', 'e.__ownableitem_id = ep.event_id')
            ->leftJoin(Prisoner::tableName() .' pr', 'pr.__person_id = ep.prisoner_id');


        if ($this->from)
            $query->andFilterWhere(['>=', 'e.date_start', $this->from]);

        if ($this->to)
            $query->andFilterWhere(['<=', 'e.date_start', $this->to]);

        $query->andFilterWhere([
            'pr.sector_id' => $this->sector_id
        ]);
        $query->andFilterWhere([
            'ep.event_id' => $event_id
        ]);
        return $query;
    }
    public function getEventsProvider()
    {
        $query = $this->getEventsQuery()
            ->groupBy(['ep.event_id'])
            ->select([
               'e.title',
               'ep.event_id',
               'participants_count' => 'count(DISTINCT ep.prisoner_id)'
            ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);

        return $dataProvider;
    }


    public function getJobsQuery($category_id = null)
    {
        $query = (new Query())
            ->from(JobNormalizedViewDays::tableName() . ' j')
            ->leftJoin(Prisoner::tableName() .' pr', 'pr.__person_id = j.prisoner_id')
            ->leftJoin(Person::tableName() .' p', 'pr.__person_id = p.__ident_id');


        if ($this->from)
            $query->andFilterWhere(['>=', 'j.at',(new \DateTime())->setTimestamp($this->from)->format('Y-m-d')]);

        if ($this->to)
            $query->andFilterWhere(['<=', 'j.at', (new \DateTime())->setTimestamp($this->to)->format('Y-m-d')]);

        $query->andFilterWhere([
            'pr.sector_id' => $this->sector_id
        ]);

        $query->andFilterWhere([
            'j.category_id' => $category_id
        ]);
        return $query;
    }
    public function getJobsProvider()
    {
        $categoryPaid = JobNormalizedViewDays::CATEGORY_PAID;
        $categoryNotPaid = JobNormalizedViewDays::CATEGORY_NOT_PAID;

        $categoryPaidTitle = JobNormalizedViewDays::getCategoriesForCombo()[JobNormalizedViewDays::CATEGORY_PAID];
        $categoryNotPaidTitle = JobNormalizedViewDays::getCategoriesForCombo()[JobNormalizedViewDays::CATEGORY_NOT_PAID];

        $query = $this->getJobsQuery()
            ->groupBy(['j.category_id'])
            ->select([
                'j.category_id',
                'category_title' => new Expression(<<<EXPRESSION
                CASE WHEN j.category_id = $categoryPaid THEN '$categoryPaidTitle' ELSE '$categoryNotPaidTitle' END

EXPRESSION
                ),
                'workers_count' => 'count(DISTINCT j.prisoner_id)'
            ]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query

        ]);

        return $dataProvider;
    }

    public function getCommitteeQuery()
    {
        $query = Committee::find()->joinWith('prisoner');
        if ($this->from)
            $query->andFilterWhere(['>=', 'date_start',$this->from]);

        if ($this->to)
            $query->andFilterWhere(['<=', 'date_start', $this->to]);


        //  $query->andFilterWhere([
        //     'prisoner.sector_id' => $this->sector_id
        //  ]);
        $query->andFilterWhere(
            [
                'assigned_to' => \Yii::$app->user->getId()
            ]
        );
        return $query;
    }


    public function getCommitteeProvider()
    {

        $dataProvider = new ActiveDataProvider([
            'query' => $this->getCommitteeQuery()

        ]);
        $dataProvider->setPagination(false);
        return $dataProvider;
    }


}