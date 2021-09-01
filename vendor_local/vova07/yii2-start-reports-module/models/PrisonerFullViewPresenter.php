<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/30/21
 * Time: 2:43 PM
 */
namespace vova07\reports\models;

use vova07\concepts\models\ConceptClass;
use vova07\concepts\models\ConceptParticipant;
use vova07\concepts\models\ConceptVisit;
use vova07\documents\models\Document;
use vova07\events\models\Event;
use vova07\jobs\models\backend\JobNormalizedViewDaysSearch;
use vova07\jobs\models\JobNormalizedViewDays;
use vova07\jobs\models\JobPaidType;
use vova07\plans\models\ProgramVisit;
use vova07\reports\Module;
use vova07\tasks\models\Committee;
use vova07\users\models\Prisoner;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use vova07\plans\models\ProgramPrisoner;

class PrisonerFullViewPresenter extends \yii\base\Model
{
    /**
     * @var $prisoner Prisoner
     */
    public $prisoner;



    public function getProgramPrisoner()
    {
        $res = array();
        foreach ($this->prisoner->getPrisonerPrograms()->all() as $programPrisoner){
            /***
             * @var $programPrisoner \vova07\plans\models\ProgramPrisoner
             */
            $programTitle = \yii\helpers\ArrayHelper::getValue($programPrisoner, 'programDict.title');
            $programDatePlan = \yii\helpers\ArrayHelper::getValue($programPrisoner, 'date_plan');
            $statusTitle = \yii\helpers\ArrayHelper::getValue($programPrisoner, 'status');
            if (
                $programPrisoner->status_id == ProgramPrisoner::STATUS_FINISHED ||
                $programPrisoner->status_id == ProgramPrisoner::STATUS_ACTIVE

            ){

                $statusContent = array();

                $rang = (new Query())->from(ProgramVisit::tableName())->select(
                    [
                         'from_date' => new Expression('min(date_visit)'),
                         'to_date' => new Expression('max(date_visit)')
                    ])->groupBy('program_prisoner_id')->where(['program_prisoner_id' => $programPrisoner->primaryKey])->one();

                if ($rang){
                    $realizateFrom = \Yii::$app->formatter->asDate($rang['from_date']);
                    $realizateTo = \Yii::$app->formatter->asDate($rang['to_date']);
                } else {
                    $realizateFrom = '';
                    $realizateTo = '';
                }

                $statusContent[] = [
                    'label' => $statusTitle,
                    'value' => $realizateFrom . '-' . $realizateTo
                ];
                $statusContent[] = [
                    'label' => \vova07\reports\Module::t('default', 'RESULT'),
                    'value' => ArrayHelper::getValue($programPrisoner, 'markTitle')
                ];
                $statusContent[] = [
                    'label' => \vova07\reports\Module::t('default', 'SPECIALITY'),
                    'value' => ArrayHelper::getValue($programPrisoner,'program.order_no')
                ];


            } else {
                $statusContent = $statusTitle;
            }

            $res[] = [
                'programTitle' => $programTitle,
                'programDatePlan' => $programDatePlan,
                'statusContent' => $statusContent
            ];
        }

        return $res;
    }
    public function getEvents()
    {
        $res = [];
        foreach ($this->prisoner->getEvents()->all() as $event){
            /***
             * @var $event Event
             */
            $res[] = [
                'eventTitle' => $event->title,
                'eventDate' => $event->dateStartJui,
            ];
        }
        return $res;
    }

    public function getConcepts()
    {
        $res = [];

            foreach ($this->prisoner->getConcepts()->orderBy('concept_dicts.title')->all() as $concept){
                //$years = (new Query())->from(ConceptClass::tableName())->select(['year' => new Expression('YEAR(DATE(FROM_UNIXTIME(at)))')])->distinct()->column();
                $res[] = [
                    'conceptId' => $concept->primaryKey,
                    'conceptTitle' => $concept->title,
                    'years' =>  $concept->dateStartJui . '-' . $concept->dateFinishJui . ' ' . "({$concept->status})"//join(',',$years),

                ];

            }
            return $res;
    }
    public function getJobs()
    {
        $res = [];
        $baseQuery = (new Query())->from(JobNormalizedViewDays::tableName())->where(['category_id'=> JobNormalizedViewDays::CATEGORY_NOT_PAID, 'prisoner_id' => $this->prisoner->primaryKey]);

        $jobNotPaidHoursQuery = clone($baseQuery)->select(['hours_totall' => new Expression('sum(hours)'), 'years' =>new Expression('GROUP_CONCAT(DISTINCT YEAR(at) ORDER BY at  SEPARATOR ",")') ]);
        $jobNotPaidHoursTotall = $jobNotPaidHoursQuery->groupBy('prisoner_id')->one();



        $res[] = [
            'label' => Module::t('default', 'JOBS_NOT_PAID_TITLE'),
            'years' => $jobNotPaidHoursTotall['years'],
            'hours_totall' => $jobNotPaidHoursTotall['hours_totall'] . ' ' . Module::t('default','hours'),

        ];



        //PAID JOB
        $jobPaidHoursQuery = (new Query())->from(['j' => JobNormalizedViewDays::tableName()])->where(['j.category_id'=> JobNormalizedViewDays::CATEGORY_PAID, 'prisoner_id' => $this->prisoner->primaryKey])
            ->leftJoin(JobPaidType::tableName() . ' t', 't.id=j.type_id');
        $jobPaidHoursQuery->select(['job_title'=>'t.title',  'years' =>new Expression('GROUP_CONCAT(DISTINCT YEAR(at) ORDER BY at  SEPARATOR ",")') ])->groupBy('prisoner_id, type_id');

        foreach ($jobPaidHoursQuery->all() as $jobPaidByType){
            $res[] = [
                'label' => Module::t('default', 'JOBS_PAID_TITLE'),
                'years' => $jobPaidByType['years'],
                'hours_totall' => $jobPaidByType['job_title'],

            ];
        }
        




        return $res;
    }

    public function getComittee()
    {
        $res = [];

        foreach ($this->prisoner->getCommities()->all() as $commitee){
            /***
             * @var $commitee Committee
             */
            //$years = (new Query())->from(ConceptClass::tableName())->select(['year' => new Expression('YEAR(DATE(FROM_UNIXTIME(at)))')])->distinct()->column();
            $res[] = [
                'subjectTitle' => $commitee->getSubject(),
                'range' => $commitee->dateStartJui . ' - ' . $commitee->dateFinishJui ,
                'result' =>  $commitee->getStatus() . ' ' . $commitee->getMark() . '/' . $commitee->getMarkBehaviour()
            ];

        }
        return $res;
    }


    public function getDocuments()
    {
        $res = [];

        foreach ($this->prisoner->getDocuments()->all() as $document){
            /***
             * @var $document Document
             */
            //$years = (new Query())->from(ConceptClass::tableName())->select(['year' => new Expression('YEAR(DATE(FROM_UNIXTIME(at)))')])->distinct()->column();
            $res[] = [
                'title' => $document->getType() . ' ' .  $document->country->iso,
                'seria' =>  Module::t('default', 'SERIA_LABEL') . ':' . $document->seria ,
                'expired' => Module::t('default', 'EXPIRED_TILL_LABEL') . ':' . $document->dateExpirationJui,

            ];

        }
        return $res;
    }

    public function getBalanceRemain()
    {
        return ArrayHelper::getValue($this->prisoner->getBalance()->one(),'remain');
    }


}