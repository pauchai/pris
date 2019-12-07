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
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;

use vova07\finances\models\Balance;
use vova07\plans\models\Program;
use vova07\plans\models\ProgramPlan;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\Requirement;
use vova07\prisons\models\Cell;
use vova07\prisons\models\Prison;
use vova07\users\models\PrisonerLocationJournal;
use vova07\prisons\models\Sector;
use vova07\users\models\Person;
use vova07\users\Module;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
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

   // public $termStartJui;
   // public $termFinishJui;
  //  public $termUdoJui;

    public static function tableName()
    {
        return 'prisoner';
    }

    public function rules()
    {
        return [
            [['__person_id','prison_id'],'required'],
            [['article'],'string'],
            [['termStartJui','termFinishJui','termUdoJui'],'date'],
            [['status_id','sector_id','cell_id'],'safe']
            //DateValidator::
        ];
    }
    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(Person::class) => Schema::TYPE_PK . ' ',
                'prison_id' => Schema::TYPE_INTEGER . ' NOT NULL',
                'sector_id' => Schema::TYPE_INTEGER ,
                'cell_id' => Schema::TYPE_INTEGER ,
                'origin_id' => Schema::TYPE_INTEGER,
                'profession' => Schema::TYPE_STRING,
                'article' => Schema::TYPE_STRING,
                'term_start' => Schema::TYPE_DATE,
                'term_finish' => Schema::TYPE_DATE,
                'term_udo' => Schema::TYPE_DATE,
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL DEFAULT ' . self::STATUS_ACTIVE,


            ],
            'indexes' =>[
                [self::class, 'term_start'],
                [self::class, 'term_finish'],
                [self::class, 'term_udo'],
            ],
            'dependsOn' => [
                Person::class
            ],
            'foreignKeys' => [
                [get_called_class(), 'prison_id',Prison::class,Prison::primaryKey()],
                [get_called_class(), 'sector_id',Sector::class,Sector::primaryKey()],
                [get_called_class(), 'cell_id',Cell::class,Cell::primaryKey()]
            ],

        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );
    }

    public function behaviors()
    {
        if (get_called_class() == self::class) {
            $behaviors = [
                'saveRelations' =>      [
                'class' => SaveRelationsBehavior::class,
                'relations' => [
                    'person'  ,
                    'ownableitem',
                    'prison',
                    'sector'
                ],
                ],


            ];


            $behaviors['changeLocation'] = [
                'class' => AttributeBehavior::class,
                'preserveNonEmptyValues' => true,
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_AFTER_INSERT => 'value',
                    \yii\db\ActiveRecord::EVENT_AFTER_UPDATE => 'value',
                ],
                'value' => function ($event) {
                    return $event->sender->resolveChangeLocation();

                },
            ];

        } else {
            $behaviors = [];
        }
        $behaviors = ArrayHelper::merge($behaviors,[
            'termStartJui' => [
                'class' => DateConvertJuiBehavior::className(),
                'attribute' => 'term_start',
                'juiAttribute' => 'termStartJui',
                'preserveNonEmptyValues' => true
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
        ]);
        return $behaviors;

    }

    public static function find()
    {
        return new PrisonerQuery(get_called_class());
    }

    public function getPerson()
    {
        return $this->hasOne(Person::class,[ '__ident_id'  => '__person_id']);
    }
    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }
    public function getPrison()
    {
        return $this->hasOne(Prison::class,['__company_id' => 'prison_id']);
    }
    public function getSector()
    {
        return $this->hasOne(Sector::class,['__ownableitem_id' => 'sector_id']);
    }
    public function getCell()
    {
        return $this->hasOne(Cell::class,['__ownableitem_id' => 'cell_id']);
    }
    public function getPrisonerPrograms()
    {
        return $this->hasMany(ProgramPrisoner::class,['prisoner_id'=>'__person_id']);
    }
    public function getPrograms()
    {
        return $this->hasMany(Program::class,['__ownableitem_id'=>'program_id'])->via('prisonerPrograms');
    }
    public function getEventParticipants()
    {
        return $this->hasMany(\vova07\events\models\EventParticipant::class,['prisoner_id' => '__person_id']);
    }
    public function getEvents()
    {
        return $this->hasMany(\vova07\events\models\Event::class,['__ownableitem_id'=>'event_id'])->via('eventParticipants');
    }
    public function getRequirements()
    {
        return $this->hasMany(Requirement::class,['prisoner_id'=>'__person_id']);
    }
    public function getProgramPlans()
    {
        return $this->hasMany(ProgramPlan::class,['prisoner_id'=>'__person_id']);
    }
    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->select(['__person_id','fio'=>'CONCAT(person.second_name, " ", person.first_name," " , person.patronymic, "," , person.birth_year)' ])->orderBy('fio asc')->joinWith('person')->asArray()->all(),'__person_id','fio');
    }

    public function getFullTitle()
    {
        return $this->person->fio . ', ' . $this->person->birth_year .', ' . $this->sector->title;
    }

    public function delete()
    {
        $this->status_id = self::STATUS_DELETED;
        return $this->save(false);
        //parent::delete();
    }

    public static function getStatusesForCombo($key = null)
    {
        $ret =  [
            self::STATUS_NOT_ACTIVE => Module::t('default','STATUS_NOT_ACTIVE'),
            self::STATUS_ACTIVE => Module::t('default','STATUS_ACTIVE'),
            self::STATUS_ETAP => Module::t('default','STATUS_ETAP'),
            self::STATUS_TERM_91 => Module::t('default','STATUS_TERM_91'),
            self::STATUS_TERM_92 => Module::t('default','STATUS_TERM_92'),
            self::STATUS_TERM_473 => Module::t('default','STATUS_TERM_473'),
            self::STATUS_TERM => Module::t('default','STATUS_TERM'),
            self::STATUS_DELETED => Module::t('default','STATUS_DELETED'),
        ];

        if (is_null($key)){
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
        return $this->hasMany(\vova07\electricity\models\Device::class,['prisoner_id'=>'__person_id']);
    }

    public function resolveChangeLocation()
    {
        if (!PrisonerLocationJournal::findOne([
            'prisoner_id' => $this->primaryKey,
            'prison_id' => $this->prison_id,
            'sector_id' => $this->sector_id,
            'cell_id' => $this->cell_id,
        ]))
        {
            $locationJournal = new PrisonerLocationJournal();
            $locationJournal->prisoner_id = $this->primaryKey;
            $locationJournal->prison_id = $this->prison_id;
            $locationJournal->sector_id = $this->sector_id;
            $locationJournal->cell_id = $this->cell_id;
            $locationJournal->save();
        }
    }
    public function attributeLabels()
    {
        return [
            '__person_id' => Module::t('labels','PERSON_ID_LABEL'),
            'prison_id' => Module::t('labels','PRISON_LABEL'),
            'sector_id' => Module::t('labels','SECTOR_LABEL'),
            'article' => Module::t('labels','ARTICLE_LABEL'),
            'termStartJui' => Module::t('labels','TERM_START_LABEL'),
            'termFinishJui' => Module::t('labels','TERM_FINISH_LABEL'),
            'termUdoJui' => Module::t('labels','TERM_UDO_LABEL'),
            'fullTitle' => Module::t('labels','FULL_TITLE_LABEL'),

         ];
    }

    public function getBalances()
    {
        return $this->hasMany(Balance::class,['prisoner_id' => '__person_id']);
    }



}
