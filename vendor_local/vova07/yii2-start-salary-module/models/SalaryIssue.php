<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\salary\models;



use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use vova07\base\components\DateConvertJuiBehavior;
use vova07\base\ModelGenerator\Helper;
use vova07\base\models\Ownableitem;
use vova07\jobs\helpers\Calendar;
use vova07\prisons\models\Company;
use vova07\prisons\models\Division;
use vova07\prisons\models\OfficerPost;
use vova07\prisons\models\Post;
use vova07\prisons\models\PostDict;
use vova07\salary\Module;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\db\Migration;
use yii\db\Schema;
use yii\helpers\ArrayHelper;
use yii\validators\DefaultValueValidator;
use yii\validators\InlineValidator;


class SalaryIssue extends  Ownableitem
{
    const STATUS_SALARY = 1;
    const STATUS_WITHHOLD = 2;
    //  const STATUS_CARD  = 3;
    const STATUS_FINISHED = 10;

    public $atFormat = 'Y-m-01';

    public function rules()
    {
        return [
            [['year', 'month_no'], 'number'],
            [['at'], 'safe'],
            [['at'], 'default', 'value' => (new \DateTime())->format($this->atFormat)],
            [['status_id'], 'default', 'value' => self::STATUS_SALARY],
        ];
    }

    public static function tableName()
    {
        return 'salary_issues';
    }


    /**
     *
     */
    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                'year' => $migration->integer()->notNull(),
                'month_no' => $migration->tinyInteger(2)->notNull(),
                'status_id' => $migration->tinyInteger()->notNull()


            ],
            'primaries' => [
                [self::class, ['year', 'month_no']]
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
                        'ownableitem',


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
        return new SalaryIssueQuery(self::class);
    }

    public function getOwnableitem()
    {
        return $this->hasOne(Ownableitem::class, ['__item_id' => '__ownableitem_id']);
    }

    public function getSalaries()
    {
        //return $this->hasMany(Salary::class,['month_no'=>'month_no', 'year' => 'year'])->orderBy('officer_id, division_id');
        return $this->hasMany(Salary::class, ['month_no' => 'month_no', 'year' => 'year']);
    }

    public function getWithHolds()
    {
        $query = SalaryWithHold::find()->where(['year' => $this->year, 'month_no' => $this->month_no]);
        $query->multiple = true;
        return $query;
    }

    public static function getStatusesForCombo()
    {
        return [
            self::STATUS_SALARY => Module::t('labels', 'STATUS_SALARY_LABEL'),
            self::STATUS_WITHHOLD => Module::t('labels', 'STATUS_WITHHOLD_LABEL'),
            //   self::STATUS_CARD => Module::t('labels', 'STATUS_CARD_LABEL'),

            self::STATUS_FINISHED => Module::t('labels', 'STATUS_FINISHED_LABEL'),
        ];
    }

    public function getStatus()
    {
        return self::getStatusesForCombo()[$this->status_id];
    }

    public function getAt($format = true)
    {
        if ($this->year && $this->month_no) {
            if ($format)
                return (new \DateTime())->setDate($this->year, $this->month_no, 1)->format($this->atFormat);
            else
                return (new \DateTime())->setDate($this->year, $this->month_no, 1);
        } else {
            if ($format)
                return (new \DateTime())->format($this->atFormat);
            else
                return (new \DateTime());
        }
    }


    public function setAt($value)
    {
        if ($value) {
            $dateTime = \DateTime::createFromFormat('Y-m-d', $value);
            $this->year = $dateTime->format('Y');
            $this->month_no = $dateTime->format('m');
        }
    }


    public function synchronize()
    {

        foreach (OfficerPost::findAll([
            'company_id' => \Yii::$app->base->company->primaryKey
        ]) as $officerPost) {
            $monthDays = \vova07\jobs\helpers\Calendar::getMonthDaysNumber((new \DateTime())->setDate($this->year, $this->month_no, '1'));
            if (Salary::findOne([
                'officer_id' => $officerPost->officer_id,
                'company_id' => $officerPost->company_id,
                'division_id' => $officerPost->division_id,
                'postdict_id' => $officerPost->postdict_id,
                'year' => $this->year,
                'month_no' => $this->month_no,
            ]))
                continue;

            $salary = $this->createSalaryForOfficerPost($officerPost,
                [
                    'work_days' => $monthDays,
                ]);
            $salary->reCalculate();
            $salary->save();
        }
    }


    public function SalaryToBalance()
    {
        foreach ($this->withHolds as $withHold) {
            /**
             * @var $withHold SalaryWithHold
             */

            $chargeAmount = $withHold->getSalaries()->totalAmount();
            $chargeAmount -= $withHold->total;


            //  $withHold->amount_card = $chargeAmount;
            $remain = $chargeAmount - $withHold->amount_card;
            if ($remain <> 0) {
                if (is_null($balance = $withHold->balance))
                    $balance = new Balance();

                $balance->officer_id = $withHold->officer_id;
                $balance->category_id = BalanceCategory::CATEGORY_SALARY;
                $balance->amount = $remain;
                $balance->at = (new \DateTime())->format('Y-m-d');
                $balance->reason = Module::t('default', 'SALARY_REMAIN {0, date, MMM, Y}', \DateTime::createFromFormat('Y-m-d', $withHold->issue->at)->getTimestamp());
                if ($balance->save()) {
                    $withHold->balance_id = $balance->primaryKey;
                    $withHold->save();
                }
            };


        }
    }

    public function getSalaryBalanceIds()
    {
        return $this->getSalaries()->select('balance_id')->distinct()->column();
    }

    /**
     * @param $officer Officer
     * @param array $attributes
     * @return null|SalaryWithHold
     */
    public function createWithHoldForOfficer($officer,$attributes = [])
    {

        $pk = ['officer_id' => $officer->primaryKey, 'year' => $this->year, 'month_no' => $this->month_no];


        $withHold = SalaryWithHold::findOne($pk);

        if (is_null($withHold)) {
            $withHold = new SalaryWithHold($pk);
        }
        $withHold->setAttributes($attributes);
        return $withHold;
    }

    /**
     *
     * @see salaryToBalance()
     */
    public function generateWithHolds()
    {
        $officerIds = $this->getSalaries()->distinct()->select('officer_id')->column();

        foreach ($officerIds as $officerId) {
            /**
             * @var $officer Officer
             */
            $officer = Officer::findOne($officerId);
            $withHold = $this->createWithHoldForOfficer($officer);
           // $withHold->reCalculate(false);
            $withHold->save();

        }
        $this->generateAmountCards(true);
    }

    public function generateAmountCards($doSave = false)
    {
        foreach ($this->withHolds as $withHold) {
            /**
             * @var $withHold SalaryWithHold
             */
            $withHold->amount_card = $withHold->calculateAmountCard();
            if ($doSave)
                $withHold->save();


        }
    }


    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (
            ($this->status_id == self::STATUS_WITHHOLD) && array_key_exists('status_id', $changedAttributes) && $changedAttributes['status_id'] <> $this->status_id
        ) {
            $this->generateWithHolds();
            //     $this->generateWithHolds();

            // } elseif ( ($this->status_id == self::STATUS_CARD) &&  array_key_exists('status_id', $changedAttributes) && $changedAttributes['status_id'] <> $this->status_id) {
            //    $this->generateAmountCards();
        } elseif (($this->status_id == self::STATUS_FINISHED) && array_key_exists('status_id', $changedAttributes) && $changedAttributes['status_id'] <> $this->status_id) {
            $this->SalaryToBalance();
        }
    }

    /**
     * @param $officerPost
     * @param $attributes
     * @return Salary
     */
    public  function createSalaryForOfficerPost($officerPost, $attributes)
    {
        $salary = new Salary(ArrayHelper::merge([
            'year' => $this->year,
            'month_no' => $this->month_no,
            'officer_id' => $officerPost->officer_id,
            'company_id' => $officerPost->company_id,
            'division_id' => $officerPost->division_id,
            'postdict_id' => $officerPost->postdict_id,
        ],
            $attributes
        )
        );
        return $salary;
    }

    /**
     * @param $officer
     * @param $attributes
     * @return Salary
     */
    public  function createSalaryForOfficer($officer, $attributes)
    {
        $salary = new Salary(ArrayHelper::merge([
            'year' => $this->year,
            'month_no' => $this->month_no,
            'officer_id' => $officer->primaryKey,
            'company_id' => $officer->company_id,
            ],
            $attributes
        )
        );
        return $salary;
    }
}
