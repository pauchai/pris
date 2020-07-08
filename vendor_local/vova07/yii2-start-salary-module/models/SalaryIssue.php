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
    const STATUS_FINISHED = 10;

    public $atFormat = 'Y-m-01';
    public function rules()
    {
        return [
            [['year', 'month_no'],'number'],
            [['at'],'safe'],
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
                [self::class, ['year','month_no']]
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
      return $this->hasOne(Ownableitem::class,['__item_id' => '__ownableitem_id']);
    }

    public function getSalaries()
    {
        //return $this->hasMany(Salary::class,['month_no'=>'month_no', 'year' => 'year'])->orderBy('officer_id, division_id');
        return $this->hasMany(Salary::class,['month_no'=>'month_no', 'year' => 'year']);
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
        if ($value)
        {
            $dateTime = \DateTime::createFromFormat('Y-m-d',$value);
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

            $salary = new Salary([
                'officer_id' => $officerPost->officer_id,
                'company_id' => $officerPost->company_id,
                'division_id' => $officerPost->division_id,
                'postdict_id' => $officerPost->postdict_id,


                'rank_id' => $officerPost->officer->rank_id,
                'year' => $this->year,
                'month_no' => $this->month_no,
                'work_days' => $monthDays,
                'amount_rank_rate' => $officerPost->officer->rank->rate


            ]);
            $salary->validate();
            $salary->reCalculate();
            $salary->save();
        }
    }

    public function salaryToBalance()
    {
        $salaryOfficerGroup = [];
        foreach ($this->salaries as $salary)
        {
            $officer_id = $salary->officer_id;
            if (!isset($salaryOfficerGroup[$officer_id])) {
                $salaryOfficerGroup[$officer_id] = [];
                $salaryOfficerGroup[$officer_id][] = $salary;
            } else
                $salaryOfficerGroup[$officer_id][] = $salary;
        }
        foreach ($salaryOfficerGroup as $officer_id => $salaries){

            $sum = 0;
            foreach ($salaries as $salary){
                $balance = $salary->balance;
                $sum += $salary->total;
            }
            if (is_null($balance)){
                $balance = new Balance();
                $balance->officer_id = $salary->officer_id;
                $balance->category_id = BalanceCategory::CATEGORY_SALARY;
                $balance->at = (new \DateTime())->format('Y-m-d');
                //$balance->reason = Module::t('default','SALARY_CHARGE');
                $balance->reason = Module::t('default','SALARY_CHARGE {0, date, MMM, Y}', \DateTime::createFromFormat('Y-m-d', $salary->issue->at)->getTimestamp());
            }
                $balance->amount = $sum;


            if ($balance->save()){
                /**
                 * @var $salary Salary
                 */

                $pk = ['officer_id' =>$balance->officer_id, 'year' => $this->year, 'month_no' => $this->month_no];
                $withHold = SalaryWithHold::findOne($pk);

                if (is_null($withHold)){
                    $withHold = new SalaryWithHold($pk);
                    $withHold->salary_balance_id = $balance->primaryKey;
                    $withHold->save();
                }
                foreach ($salaries as $salary){
                    $salary->balance_id = $balance->primaryKey;
                    $salary->save();
                }
            };

        }


    }

    public function withHoldToBalance()
    {
        foreach ($this->withHolds as  $withHold)
        {
            /**
             * @var $salary Salary
             */
            if (is_null($balance = $withHold->balance))
                $balance = new Balance();

            $balance->officer_id = $withHold->officer_id;
            $balance->category_id = BalanceCategory::CATEGORY_SALARY;
            $balance->amount = -1 * $withHold->getTotal();
            $balance->at = (new \DateTime())->format('Y-m-d');
            $balance->reason = Module::t('default','SALARY_WITHHOLD {0, date, MMM, Y}', \DateTime::createFromFormat('Y-m-d', $withHold->issue->at)->getTimestamp());
            if ($balance->save()){
            $withHold->balance_id = $balance->primaryKey;
             $withHold->save();
            };



        }
    }

    public function getSalaryBalanceIds()
    {
       return  $this->getSalaries()->select('balance_id')->distinct()->column();
    }

    /**
     * @deprecated
     * @see salaryToBalance()
     */
    public function generateWithHolds()
    {
        $salaryBalances = Balance::find()->where(['__ownableitem_id' => self::getSalaryBalanceIds() ])->all()      ;
        foreach ($salaryBalances as $balance){
            /**
             * @var $salary Salary
             */

            $pk = ['officer_id' =>$balance->officer_id, 'year' => $this->year, 'month_no' => $this->month_no];
            $withHold = SalaryWithHold::findOne($pk);

            if (is_null($withHold)){
                $withHold = new SalaryWithHold($pk);
                $withHold->save();
            }

        }
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert,$changedAttributes);
        if (
            ($this->status_id == self::STATUS_WITHHOLD) &&  array_key_exists('status_id', $changedAttributes) && $changedAttributes['status_id'] <> $this->status_id
        ){
            $this->salaryToBalance();
       //     $this->generateWithHolds();
        } elseif ( ($this->status_id == self::STATUS_FINISHED) &&  array_key_exists('status_id', $changedAttributes) && $changedAttributes['status_id'] <> $this->status_id) {
            $this->withHoldToBalance();
        }
    }
}
