<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models;




use vova07\concepts\models\Concept;
use vova07\concepts\models\ConceptClass;
use vova07\events\models\Event;
use vova07\plans\components\SummarizedDataProvider;
use vova07\plans\controllers\backend\ProgramVisitsController;
use vova07\rbac\Module;
use vova07\users\helpers\UserHelper;
use vova07\users\models\User;
use yii\base\Model;



class SummarizedModel extends  Model
{
    public $at;
    public $format = 'Y-m-d';

    public static $educatorRoles = [
        Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_HEAD,
        Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EXPERT,
        Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_EDUCATOR,
    ];

    public static $sociologistRoles = [
        Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_SOCIOLOGIST
    ];
    public static $psychologistRoles = [
        Module::ROLE_SOC_REINTEGRATION_DEPARTMENT_PSYCHOLOGIST
    ];
    public function getEducatorActivitationsCount()
    {
        return $this->getEventsByRoles(self::$educatorRoles)->count() +
            $this->getProgramsVisitsDistinctsProgramByRoles(self::$educatorRoles)->count() +
           $this->getConceptClassesByRoles(self::$educatorRoles)->count();

    }

    public function getEducatorParticipantsCount()
    {
        return $this->getEventsParticipantsByRoles(self::$educatorRoles)+
            $this->getProgramVisitsByRoles(self::$educatorRoles)->count() +
            $this->getConceptClassesVisitsByRoles(self::$educatorRoles);
    }

    public function getSociologistActivitationsCount()
    {
        return $this->getEventsByRoles(self::$sociologistRoles)->count() +
            $this->getProgramsVisitsDistinctsProgramByRoles(self::$sociologistRoles)->count() +
            $this->getConceptClassesByRoles(self::$sociologistRoles)->count();

    }

    public function getSociologistParticipantsCount()
    {
        return $this->getEventsParticipantsByRoles(self::$sociologistRoles)+
            $this->getProgramVisitsByRoles(self::$sociologistRoles)->count() +
            $this->getConceptClassesVisitsByRoles(self::$sociologistRoles);
    }

    public function getPsychologistActivitationsCount()
    {
        return $this->getEventsByRoles(self::$psychologistRoles)->count() +
            $this->getProgramsVisitsDistinctsProgramByRoles(self::$psychologistRoles)->count() +
            $this->getConceptClassesByRoles(self::$psychologistRoles)->count();

    }

    public function getPsychologistParticipantsCount()
    {
        return $this->getEventsParticipantsByRoles(self::$psychologistRoles)+
            $this->getProgramVisitsByRoles(self::$psychologistRoles)->count() +
            $this->getConceptClassesVisitsByRoles(self::$psychologistRoles);
    }

//Events

    public function getEventsByRoles($roles)
    {


        $dateStartFrom = \DateTime::createFromFormat($this->format,$this->at)->setTime(0,0,0);
        $dateStartTo = (clone $dateStartFrom)->add(new \DateInterval('PT24H'));
        return Event::find()->andWhere([
            'assigned_to' => $this->getUserIdsByRolesQuery($roles),
          ]
        )->andWhere(
             ['>=', 'date_start', $dateStartFrom->getTimestamp()  ]
        )->andWhere(
            ['<=', 'date_start', $dateStartTo->getTimestamp()  ]
        );

    }



    public function getEventsParticipantsByRoles($roles)
    {
        $cnt = 0;

        foreach ($this->getEventsByRoles($roles)->all() as $event)
        {
            /**
             * @var $event Event
             */
            $cnt += $event->getParticipants()->count();
        }
        return $cnt;
    }

    //Programs
    public function getProgramsVisitsDistinctsProgramByRoles($roles)
    {
       return $this->getProgramVisitsByRoles($roles)->select(['program_prisoners.program_id'])->distinct();


    }



    public function getProgramVisitsByRoles($roles)
    {
        return ProgramVisit::find()->joinWith('programPrisoner')->joinWith('programPrisoner.program')->andWhere([
                'programs.assigned_to' => $this->getUserIdsByRolesQuery($roles),
            ]
        )->presented()->andWhere(['program_visits.date_visit' => $this->at]);
    }

    //Concepts
    public function getConceptClassesByRoles($roles)
    {
        $dateStartFrom = \DateTime::createFromFormat($this->format,$this->at)->setTime(0,0,0);
        $dateStartTo = (clone $dateStartFrom)->add(new \DateInterval('PT24H'));

        return ConceptClass::find()->joinWith('concept')->
            andWhere([
           'concepts.assigned_to' => $this->getUserIdsByRolesQuery($roles)
        ])->andWhere(
            ['>=', 'at', $dateStartFrom->getTimestamp()  ]
        )->andWhere(
            ['<=', 'at', $dateStartTo->getTimestamp()  ]
        );
    }

    public function getConceptClassesVisitsByRoles($roles)
    {
        $cnt = 0;
        /**
         * @var $conceptClass ConceptClass
         */
        foreach ($this->getConceptClassesByRoles($roles)->all() as $conceptClass)
        {
            /**
             * @var $program Program
             */
            $cnt += $conceptClass->getVisits()->presented()->count();
        }
        return $cnt;
    }
    private function getUserIdsByRolesQuery($roles)
    {
        return UserHelper::getUserIdsByRolesQuery($roles);
    }


}
