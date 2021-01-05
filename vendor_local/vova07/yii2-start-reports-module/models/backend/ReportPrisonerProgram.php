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
use yii\db\QueryBuilder;
use yii\helpers\ArrayHelper;
use yii\jui\DatePicker;

class ReportPrisonerProgram extends PrisonerView
{
    public $year;


    public function rules()
    {
        return [
            [['year', 'sector_id'],'safe'],


        ];
    }

    public static function getYearsForFilterCombo()
    {
        $programQuery = ProgramPrisoner::find()->select(['year' =>'date_plan' ])->andWhere(new Expression('not ISNULL(date_plan)'));
        $eventQuery = Event::find()->select(['year' => new \yii\db\Expression("YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL date_start SECOND)) ")]);
        $combineQuery = $programQuery->union($eventQuery);
        $resultArray = $combineQuery->orderBy(['year' => SORT_ASC])->asArray()->all();
        $resultArray = ArrayHelper::map($resultArray,'year','year') ;
       // sort($resultArray);




//orderBy(['year' => SORT_ASC])

        return $resultArray;
    }


}