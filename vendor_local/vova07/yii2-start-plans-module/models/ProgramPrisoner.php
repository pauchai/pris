<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Item;
use vova07\base\models\Ownableitem;
use vova07\countries\models\Country;
use vova07\plans\Module;
use vova07\prisons\models\Prison;
use vova07\users\models\Prisoner;
use yii\behaviors\SluggableBehavior;
use yii\db\BaseActiveRecord;
use yii\db\Expression;
use yii\db\Schema;
use yii\helpers\ArrayHelper;


class ProgramPrisoner extends  Ownableitem
{
    const MARK_NOT_SATISFACTORY = 0;
    const MARK_SATISFACTORY = 1;
    const MARK_GOOD = 2;


    const STATUS_INIT=0;
    const STATUS_PLANED =9;
    const STATUS_ACTIVE =10;
    const STATUS_FAILED = 11;
    const STATUS_FINISHED =12;


    public static function tableName()
    {
        return 'program_prisoners';
    }
    public function rules()
    {
        return [
            [['programdict_id', 'prison_id','prisoner_id','status_id'], 'required'],

            [['programdict_id','prison_id','prisoner_id','program_id'],'unique','targetAttribute' => ['programdict_id','prison_id', 'prisoner_id','program_id']],
            [['date_plan'],'integer']
        ];
    }

    /**
     *
     */
    public static function getMetadata()
    {
        $metadata = [
            'fields' => [
                Helper::getRelatedModelIdFieldName(OwnableItem::class) => Schema::TYPE_PK . ' ',
                'programdict_id' =>Schema::TYPE_INTEGER. ' NOT NULL',
                'prison_id' => Schema::TYPE_INTEGER. ' NOT NULL',
                'prisoner_id' => Schema::TYPE_INTEGER. ' NOT NULL',
                'date_plan' => Schema::TYPE_INTEGER. '',
                'program_id' => Schema::TYPE_INTEGER. '',
                'mark_id' => Schema::TYPE_TINYINT. ' ',
                'status_id' => Schema::TYPE_TINYINT . ' NOT NULL',
            ],
            //'primaries' => [
            //    [self::class,['program_id','prisoner_id']]
            //],

            'index' => [

              [self::class,'mark_id'],
                [self::class,'status_id'],
                [self::class,'program_id'],
            ],
            'foreignKeys' => [
                //[self::class, 'program_id',Program::class,Program::primaryKey()],

                [self::class, 'prison_id',Prison::class,Prison::primaryKey()],
                [self::class, 'programdict_id',ProgramDict::class,ProgramDict::primaryKey()],
                [self::class, 'prisoner_id',Prisoner::class,Prisoner::primaryKey()],
            ],


        ];
        return ArrayHelper::merge($metadata, parent::getMetaDataForMerging() );

    }

    public function behaviors()
    {

        if (get_called_class() == self::class) {
            $behaviors = [
                'saveRelations' => [
                    'class' => SaveRelationsBehavior::class,
                    'relations' => [
                        'ownableitem',
                      //  'program',
                      //  'prisoner',



                    ],
                ]

            ];
        } else {
            $behaviors = [];
        }
        return $behaviors;
    }

    public static function find()
    {
        return new ProgramPrisonerQuery(get_called_class());
    }

    public function getOwnableitem()
    {
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }

    public function getPrisoner()
    {
        return $this->hasOne(Prisoner::class,['__person_id' => 'prisoner_id']);
    }

    public function getProgramDict()
    {
        return $this->hasOne(ProgramDict::class,['__ownableitem_id'=>'programdict_id']);
    }

    public function getProgram()
    {
        return $this->hasOne(Program::class,['__ownableitem_id' => 'program_id']);
    }
    public function getPrison()
    {
        return $this->hasOne(Prison::class,['__company_id' => 'prison_id']);
    }

    public static function getListForCombo()
    {
        return ArrayHelper::map(self::find()->joinWith(['program'=>function($q){$q->joinWith('programDict');}])->asArray()->all(),'__ownableitem_id','program.programDict.title');
    }

    public static function getStatusesForCombo()
    {
        return [
            self::STATUS_INIT => Module::t('programs','STATUS_INIT'),
            self::STATUS_PLANED => Module::t('programs','STATUS_PLANED'),
            self::STATUS_ACTIVE => Module::t('programs','STATUS_ACTIVE'),
            self::STATUS_FAILED => Module::t('programs','STATUS_FAILED'),
            self::STATUS_FINISHED => Module::t('programs','STATUS_FINISHED'),
        ];
    }

    /**
     * @return ProgramVisitQuery
     */
    public function getVisits()
    {
        return $this->hasMany(ProgramVisit::class,[
            'program_prisoner_id'=>'__ownableitem_id',

            ]);
    }

    /**
     * @return int|null
     */
    public function resolveMark(){

        $presentedCount = $this->getVisits()->presented()->count();
        $exceptDoesntPresentedValidCount= $this->getVisits()->exceptDoesntPresentedValid()->count();

        $markIndicator = 0;
        if ($exceptDoesntPresentedValidCount){
            $markIndicator = round($presentedCount/$exceptDoesntPresentedValidCount * 100);
        }
        switch(true){
            case ($markIndicator<60):$mark = self::MARK_NOT_SATISFACTORY;break;
            case ($markIndicator<80):$mark = self::MARK_SATISFACTORY;break;
            default: $mark = self::MARK_GOOD;

        }
        return $mark;
    }


    public static function getMarksForCombo()
    {
        return [
            self::MARK_NOT_SATISFACTORY => Module::t('programs','MARK_NOT_SATISFACTORY'),
            self::MARK_SATISFACTORY => Module::t('programs','MARK_SATISFACTORY'),
            self::MARK_GOOD => Module::t('programs','MARK_GOOD'),
        ];
    }
    public function getMarkTitle()
    {
       return self::getMarksForCombo()[$this->mark_id];
    }
    public static function getMarkTitleById($mark_id)
    {
        if (is_null($mark_id)){
            return '';
        } else {
            return self::getMarksForCombo()[$mark_id];
        }

    }

    public static function mapMarkStyle()
    {
        return [
            self::MARK_NOT_SATISFACTORY => 'danger',
            self::MARK_SATISFACTORY => 'info',
            self::MARK_GOOD => 'success',
        ];
    }
    public static function resolveMarkStyleById($mark_id)
    {
        return self::mapMarkStyle()[$mark_id];
    }

    public static function getYearsForFilterCombo()
    {
        return ArrayHelper::map(self::find()->select('date_plan')->distinct()->asArray()->all(),'date_plan','date_plan');
    }

    public static function getProgramDistinctForCombo()
    {
        return ArrayHelper::map(self::find()->select('programdict_id')->with('programDict')->distinct()->asArray()->all(),'programdict_id','programDict.title');
    }


}
