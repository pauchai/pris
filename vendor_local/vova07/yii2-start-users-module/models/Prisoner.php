<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\users\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsTrait;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\components\DateTermDurationBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\concepts\models\Concept;
use vova07\concepts\models\ConceptParticipant;
use vova07\countries\models\Country;

use vova07\documents\models\Document;
use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\Balance;
use vova07\jobs\models\JobNormalizedViewDays;
use vova07\plans\models\PrisonerPlan;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramDict;
use vova07\plans\models\ProgramPlan;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\Requirement;
use vova07\prisons\models\Cell;
use vova07\prisons\models\Company;
use vova07\prisons\models\Penalty;
use vova07\prisons\models\Prison;
use vova07\prisons\models\PrisonerSecurity;
use vova07\psycho\models\PsyCharacteristic;
use vova07\psycho\models\PsyRisk;
use vova07\psycho\models\PsyTest;
use vova07\socio\models\MaritalState;
use vova07\socio\models\Relation;
use vova07\users\models\backend\PrisonerViewSearch;
use vova07\users\models\PrisonerLocationJournal;
use vova07\prisons\models\Sector;
use vova07\users\models\Person;
use vova07\users\Module;
use yii\base\Event;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DateValidator;


class Prisoner extends  OwnableItem
{

    use SaveRelationsTrait;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_ETAP = 11;

    const STATUS_TERM_91 = 50;
    const STATUS_TERM_92 = 51;
    const STATUS_TERM_473 = 52;
    const STATUS_TERM = 53;

    const STATUS_DELETED = 99;

    const UDO3_3 = 3 / 4;
    const UDO2_3 = 2 / 3;
    const UDO1_2 = 1 / 2;


    // public $termStartJui;
    // public $termFinishJui;
    //  public $termUdoJui;

    public static function tableName()
    {
        return 'prisoner';
    }

    public static function getTermStatuses()
    {
        return [
            self::STATUS_TERM,
            self::STATUS_TERM_91,
            self::STATUS_TERM_473,
            self::STATUS_TERM_92,
        ];
    }

    public function rules()
    {
        return [
            [['__person_id', 'prison_id'], 'required'],
            [['article'], 'string'],
            [['termStartJui', 'termFinishJui', 'termUdoJui'], 'date'],
            [['status_id', 'sector_id', 'cell_id'], 'safe'],
            [['criminal_records'], 'integer'],
            //DateValidator::
        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(Person::class) => Schema::TYPE_PK . ' ',
                'prison_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'sector_id' => Schema::TYPE_INTEGER,
                'cell_id' => Schema::TYPE_INTEGER,
                'origin_id' => Schema::TYPE_INTEGER,
                'profession' => Schema::TYPE_STRING,
                'article' => Schema::TYPE_STRING,
                'term_start' => Schema::TYPE_DATE,
                'term_finish' => Schema::TYPE_DATE,
                'term_finish_origin' => Schema::TYPE_DATE,
                'term_udo' => Schema::TYPE_DATE,
                'criminal_records' => $migration->tinyInteger(),
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL DEFAULT ' . self::STATUS_ACTIVE,


            ],
            'indexes' => [
                [self::class, 'term_start'],
                [self::class, 'term_finish'],
                [self::class, 'term_udo'],
            ],
            'dependsOn' => [
                Person::class
            ],
            'foreignKeys' => [
                [get_called_class(), 'prison_id', Prison::class, Prison::primaryKey()],
                [get_called_class(), 'sector_id', Sector::class, Sector::primaryKey()],
                [get_called_class(), 'cell_id', Cell::class, Cell::primaryKey()]
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging());
    }

    public function behaviors()
    {
        if (get_called_class() == self::class) {
            $behaviors = [
                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'person',
                        'ownableitem',
                        'prison',
                        'sector'
                    ],
                ],


            ];


        } else {
            $behaviors = [];
        }
        $behaviors = ArrayHelper::merge($behaviors, [
            'termStartJui' => [
                'class' => DateConvertJuiBehavior::className(),
                'attribute' => 'term_start',
                'juiAttribute' => 'termStartJui',
              //  'preserveNonEmptyValues' => true
            ],
            'termFinishJui' => [
                'class' => DateConvertJuiBehavior::className(),
                'attribute' => 'term_finish',
                'juiAttribute' => 'termFinishJui'
            ],
            'termUdoJui' => [
                'class' => DateConvertJuiBehavior::className(),
                'attribute' => 'term_udo',
                'juiAttribute' => 'termUdoJui'
            ],
            'termFinishOriginJui' => [
                'class' => DateConvertJuiBehavior::className(),
                'attribute' => 'term_finish_origin',
                'juiAttribute' => 'termFinishOriginJui'
            ],
//            'termFinishOriginDuration' => [
//                'class' => DateTermDurationBehavior::className(),
//                'attributeTermStart' => 'term_start',
//                'attributeTermFinish' => 'term_finish_origin',
//                'durationAttribute' => 'termFinishOriginDuration'
//            ],
        ]);
        return $behaviors;

    }

    public static function find()
    {
        return new PrisonerQuery(get_called_class());
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class, ['__ownableitem_id' => '__person_id']);
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }

    public function getPrison()
    {
        return $this->hasOne(Prison::class, ['__company_id' => 'prison_id']);
    }

    public function getSector()
    {
        return $this->hasOne(Sector::class, ['__ownableitem_id' => 'sector_id']);
    }

    public function getCell()
    {
        return $this->hasOne(Cell::class, ['__ownableitem_id' => 'cell_id']);
    }

    public function getPrisonerPrograms()
    {
        return $this->hasMany(ProgramPrisoner::class, ['prisoner_id' => '__person_id']);
    }

    public function getPrograms()
    {
        return $this->hasMany(Program::class, ['__ownableitem_id' => 'program_id'])->via('prisonerPrograms');
    }

    public function getEventParticipants()
    {
        return $this->hasMany(\vova07\events\models\EventParticipant::class, ['prisoner_id' => '__person_id']);
    }

    public function getEvents()
    {
        return $this->hasMany(\vova07\events\models\Event::class, ['__ownableitem_id' => 'event_id'])->via('eventParticipants');
    }

    public function getCommities()
    {
        return $this->hasMany(\vova07\tasks\models\Committee::class, ['prisoner_id' => '__person_id']);
    }
    public function getJobs()
    {
        return $this->hasMany(\vova07\jobs\models\JobPaidList::class, ['assigned_to' => '__person_id']);
    }
    public function getNormilizedJobsView()
    {
        return $this->hasMany(JobNormalizedViewDays::class, ['prisoner_id' => '__person_id']);
    }

    public function getRequirements()
    {
        return $this->hasMany(Requirement::class, ['prisoner_id' => '__person_id']);
    }

    public function getProgramPlans()
    {
        return $this->hasMany(ProgramPlan::class, ['prisoner_id' => '__person_id']);
    }
    public function getLocationJournal()
    {
        return $this->hasMany(PrisonerLocationJournal::class, ['prisoner_id' => '__person_id']);
    }

    public static function getListForCombo()
    {

        return ArrayHelper::map(self::find()->select(['__person_id', 'fio' => 'CONCAT(person.second_name, " ", person.first_name," " , person.patronymic, "," , CASE WHEN isNull(person.birth_year) THEN 0 Else person.birth_year END)'])->orderBy('fio asc')->joinWith('person')->andWhere(['<>', 'status_id', Prisoner::STATUS_DELETED])->asArray()->all(), '__person_id', 'fio');
    }

    public function getFullTitle($showSector = false)
    {
        $ret = $this->person->fio . ', ' . $this->person->birth_year;
        if ($this->sector_id && $showSector)
            $ret .= ', ' . ($this->sector->title);
        return $ret;
    }

    public function delete()
    {
        $this->status_id = self::STATUS_DELETED;
        return $this->save(false);
        //parent::delete();
    }

    public static function getStatusesForCombo($key = null)
    {
        $ret = [
            self::STATUS_NOT_ACTIVE => Module::t('default', 'STATUS_NOT_ACTIVE'),
            self::STATUS_ACTIVE => Module::t('default', 'STATUS_ACTIVE'),
            self::STATUS_ETAP => Module::t('default', 'STATUS_ETAP'),
            self::STATUS_TERM_91 => Module::t('default', 'STATUS_TERM_91'),
            self::STATUS_TERM_92 => Module::t('default', 'STATUS_TERM_92'),
            self::STATUS_TERM_473 => Module::t('default', 'STATUS_TERM_473'),
            self::STATUS_TERM => Module::t('default', 'STATUS_TERM'),
            self::STATUS_DELETED => Module::t('default', 'STATUS_DELETED'),
        ];

        if (is_null($key)) {
            return $ret;
        } else {
            return $ret[$key];
        }


    }

    public function getStatus()
    {
        return self::getStatusesForCombo($this->status_id);
    }


    public function getDevices()
    {
        return $this->hasMany(\vova07\electricity\models\Device::class, ['prisoner_id' => '__person_id']);
    }

    public function resolveChangeLocation()
    {
        /* if (!PrisonerLocationJournal::findOne([
             'prisoner_id' => $this->primaryKey,
             'prison_id' => $this->prison_id,
             'sector_id' => $this->sector_id,
             'cell_id' => $this->cell_id,
         ]))*/
        {
            $locationJournal = new PrisonerLocationJournal();
            $locationJournal->prisoner_id = $this->primaryKey;
            $locationJournal->prison_id = $this->prison_id;
            $locationJournal->sector_id = $this->sector_id;
            $locationJournal->cell_id = $this->cell_id;
            $locationJournal->status_id = $this->status_id;
            $locationJournal->save();
        }
    }

    public function attributeLabels()
    {
        return [
            '__person_id' => Module::t('labels', 'PERSON_ID_LABEL'),
            'prison_id' => Module::t('labels', 'PRISON_LABEL'),
            'sector_id' => Module::t('labels', 'SECTOR_LABEL'),
            'article' => Module::t('labels', 'ARTICLE_LABEL'),
            'termStartJui' => Module::t('labels', 'TERM_START_LABEL'),
            'term_start' => Module::t('labels', 'TERM_START_LABEL'),
            'termFinishJui' => Module::t('labels', 'TERM_FINISH_LABEL'),
            'term_finish' => Module::t('labels', 'TERM_FINISH_LABEL'),
            'termUdoJui' => Module::t('labels', 'TERM_UDO_LABEL'),
            'term_udo' => Module::t('labels', 'TERM_UDO_LABEL'),
            'fullTitle' => Module::t('labels', 'FULL_TITLE_LABEL'),
            'status' => Module::t('labels', 'PRISONER_STATUS_LABEL'),
            'criminal_records' => Module::t('labels', 'PRISONER_CRIMINAL_RECORDS_LABEL'),
            'takenSpeciality' => Module::t('labels', 'PRISONER_TAKEN_SPECIALITY_LABEL'),

            'term' => Module::t('labels', 'PRISONER_TERM_LABEL'),



        ];
    }

    public function getBalances()
    {
        return $this->hasMany(Balance::class, ['prisoner_id' => '__person_id']);
    }

    public function getBalance()
    {
        return $this->hasOne(BalanceByPrisonerView::class, ['prisoner_id' => '__person_id']);
    }
    public function getPrisonerSecurity()
    {
        return $this->hasOne(PrisonerSecurity::class, ['prisoner_id' => '__person_id']);
    }

    public function getPrisonerPlan()
    {
        return $this->hasOne(PrisonerPlan::class,['__prisoner_id' => '__person_id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
        if (
            array_key_exists('sector_id', $changedAttributes) && $changedAttributes['sector_id'] <> $this->sector_id ||
            array_key_exists('prison_id', $changedAttributes) && $changedAttributes['prison_id'] <> $this->prison_id ||
            array_key_exists('cell_id', $changedAttributes) && $changedAttributes['cell_id'] <> $this->cell_id ||
            array_key_exists('status_id', $changedAttributes) && $changedAttributes['status_id'] <> $this->status_id
        ){
            $this->resolveChangeLocation($changedAttributes);
        }
    }

    public function getCharacteristic()
    {
        return $this->hasOne(PsyCharacteristic::class, ['__person_id' => '__person_id']);
    }

    public function getConceptParticipants()
    {
        return $this->hasMany(ConceptParticipant::class, ['prisoner_id' => '__person_id']);
    }
    public function getConcepts()
    {
        return $this->hasMany(Concept::class, ['__ownableitem_id' => 'concept_id'])->via('conceptParticipants');
    }

    public function getTests()
    {
        return $this->hasMany(PsyTest::class, ['prisoner_id' => '__person_id']);
    }

    public function getPenalties()
    {
        return $this->hasMany(Penalty::class, ['prisoner_id' => '__person_id']);

    }

    public function getDocuments()
    {
        return $this->hasMany(Document::class,['person_id' => '__person_id']);
    }
    public function getIdentDoc()
    {
        return  $this->getDocuments()->identification()->one();
    }
    public function getIdentDocs()
    {
        return  $this->getDocuments()->identification();
    }

    public function getTerm()
    {
        if ($this->term_start && $this->term_finish){
            $termStart = new \DateTime( $this->term_start);
            $termFinish = ( new \DateTime( $this->term_finish))->modify('+1 day');

            $interval = date_diff($termStart, $termFinish);

            return $interval->format('%y years %m months %d days');
        }
            return null;
    }

    public function getCalculatedUDO()
    {
        $termStart = new \DateTime( $this->term_start);
        $termFinish = ( new \DateTime( $this->term_finish));
        $interval = date_diff($termStart, $termFinish);
        $days1_3 =round($interval->days * self::UDO2_3);
        return $termStart->modify('+'.$days1_3 . ' days')->format('d-m-Y');

    }

    public function getTakenSpeciality()
    {
       /**
        * @TODO
        */

       $query = $this->getPrisonerPrograms()->andWhere([
           'program_prisoners.prison_id' => Company::ID_PRISON_PU1,
           'program_prisoners.programdict_id' => ProgramDict::SPECIALITY_PROGRAM_DICT_ID,
       ]);
       $query->andWhere(new Expression("NOT ISNULL(program_prisoners.program_id)"));
       $query->joinWith('program');
       $query->andWhere(['program_prisoners.status_id' => ProgramPrisoner::STATUS_FINISHED]);

       $prisonerProgram = $query->one();

       return ArrayHelper::getValue($prisonerProgram, 'program.order_no');

    }

    public function getRelations()
    {
        return $this->hasMany(Relation::class, ['person_id' => '__person_id']);
    }
    public function getMaritals()
    {
        return $this->hasMany(MaritalState::class, ['__person_id' => '__person_id']);
    }
    public function getTermDateFromJournal()
    {
        $journal = PrisonerLocationJournal::find()->andWhere(['prisoner_id' => $this->primaryKey])->andWhere(['in','status_id', [self::STATUS_TERM,self::STATUS_TERM_91, self::STATUS_TERM_92, self::STATUS_TERM_473]])->one();
        return ArrayHelper::getValue($journal,'atJui');
    }

}
