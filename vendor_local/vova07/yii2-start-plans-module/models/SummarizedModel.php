<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models;




use vova07\concepts\models\Concept;
use vova07\events\models\Event;
use vova07\plans\components\SummarizedDataProvider;
use vova07\rbac\Module;
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
            $this->getProgramsByRoles(self::$educatorRoles)->count() +
           $this->getConceptsByRoles(self::$educatorRoles)->count();

    }

    public function getEducatorParticipantsCount()
    {
        return $this->getEventsParticipantsByRoles(self::$educatorRoles)+
            $this->getProgramVisitsByRoles(self::$educatorRoles) +
            $this->getConceptParticipantsByRoles(self::$educatorRoles);
    }

    public function getSociologistActivitationsCount()
    {
        return $this->getEventsByRoles(self::$sociologistRoles)->count() +
            $this->getProgramsByRoles(self::$sociologistRoles)->count() +
            $this->getConceptsByRoles(self::$sociologistRoles)->count();

    }

    public function getSociologistParticipantsCount()
    {
        return $this->getEventsParticipantsByRoles(self::$sociologistRoles)+
            $this->getProgramVisitsByRoles(self::$sociologistRoles) +
            $this->getConceptParticipantsByRoles(self::$sociologistRoles);
    }

    public function getPsychologistActivitationsCount()
    {
        return $this->getEventsByRoles(self::$psychologistRoles)->count() +
            $this->getProgramsByRoles(self::$psychologistRoles)->count() +
            $this->getConceptsByRoles(self::$psychologistRoles)->count();

    }

    public function getPsychologistParticipantsCount()
    {
        return $this->getEventsParticipantsByRoles(self::$psychologistRoles)+
            $this->getProgramVisitsByRoles(self::$psychologistRoles) +
            $this->getConceptParticipantsByRoles(self::$psychologistRoles);
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
    public function getProgramsByRoles($roles)
    {
       return Program::find()->andWhere([
                'assigned_to' => $this->getUserIdsByRolesQuery($roles),
            ]
        )->andWhere(
            ['date_start' => $this->at  ]);


    }



    public function getProgramVisitsByRoles($roles)
    {
        $cnt = 0;

        foreach ($this->getProgramsByRoles($roles)->all() as $program)
        {
            /**
             * @var $program Program
             */
            $cnt += $program->getProgramVisits()->presented()->count();
        }
        return $cnt;
    }

    //Concepts
    public function getConceptsByRoles($roles)
    {
        $dateStartFrom = \DateTime::createFromFormat($this->format,$this->at)->setTime(0,0,0);
        $dateStartTo = (clone $dateStartFrom)->add(new \DateInterval('PT24H'));

        return Concept::find()->andWhere([
           'assigned_to' => $this->getUserIdsByRolesQuery($roles)
        ])->andWhere(
            ['>=', 'date_start', $dateStartFrom->getTimestamp()  ]
        )->andWhere(
            ['<=', 'date_start', $dateStartTo->getTimestamp()  ]
        );
    }

    public function getConceptParticipantsByRoles($roles)
    {
        $cnt = 0;
        /**
         * @var $concept Concept
         */
        foreach ($this->getConceptsByRoles($roles)->all() as $concept)
        {
            /**
             * @var $program Program
             */
            $cnt += $concept->getParticipants()->count();
        }
        return $cnt;
    }
    private function getUserIdsByRolesQuery($roles)
    {
        $query = User::find()->select(['__ident_id']);

        foreach ($roles as $role ){
            $query->orWhere([
                'role' => $role,
            ]);
        }
        return $query;
    }


}
