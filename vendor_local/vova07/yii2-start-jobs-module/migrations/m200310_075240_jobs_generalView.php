<?php

use yii\db\Migration;
USE vova07\jobs\models\JobsGeneralListView;

/**
 * Class m200310_075240_generalView
 */
class m200310_075240_jobs_generalView extends Migration
{
    const JOBS_PAID = 1;
    const JOBS_NOT_PAID = 2;
    public $subViewName = 'jobsGeneralList_SUB';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createView();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->db->createCommand()->dropView(JobsGeneralListView::tableName())->execute();
        return $this->db->createCommand()->dropView($this->subViewName)->execute();
    }


    public function createView()
    {

        $time = $this->beginCommand("create view " . $this->db->quoteTableName($this->subViewName));
        $this->db->createCommand()->createView($this->subViewName,
            $this->getUnionQuery()
        )->execute();
        $this->endCommand($time);

        $time = $this->beginCommand("create view " . $this->db->quoteTableName(JobsGeneralListView::tableName()));

        $columns = ['prisoner_id','year'];
        //$i=1;
        for ($i=1; $i<=12; $i++){
            //$columns['m' . $i. 'p'] = new \yii\db\Expression('sum((case when (type_id = :type_id AND month_no=:month_no)
            //then hours end))', [':type_id' => self::JOBS_PAID, ':month_no' => $i]);
            $columns['m' . $i. 'p'] = 'sum((case when (type_id = ' . self::JOBS_PAID.  ' AND month_no=' . $i . ') then hours end))';
            $columns['m' . $i. 'np'] = 'sum((case when (type_id = ' . self::JOBS_NOT_PAID.  ' AND month_no=' . $i . ') then hours end))';
//            $columns['m' . $i . 'np'] = new \yii\db\Expression('sum((case when (type_id = :type_id AND month_no=:month_no)
 //          then hours end))', [':type_id' => self::JOBS_NOT_PAID, ':month_no' => $i]);
        }


        $this->db->createCommand()->createView(JobsGeneralListView::tableName(),
            (new \yii\db\Query())->select($columns)->from($this->subViewName)->groupBy('prisoner_id, year')
            )->execute();
        $this->endCommand($time);
    }




    private function getUnionQuery()
    {
        $columns = $this->generateJobColumns();
        $jobPaidColumns = $columns;
        $jobPaidColumns['type_id'] = new \yii\db\Expression(self::JOBS_PAID);

        $jobNotPaidColumns = $columns;
        $jobNotPaidColumns['type_id'] = new \yii\db\Expression(self::JOBS_NOT_PAID);

        return (new \yii\db\Query())->select(
           $jobPaidColumns
        )->from([\vova07\jobs\models\JobPaid::tableName()])->union(
            (new \yii\db\Query())->select(
                $jobNotPaidColumns
            )->from([\vova07\jobs\models\JobNotPaid::tableName()])
        );
    }

    private function generateJobColumns()
    {
        $daysColumns = [];
        for($i=1; $i<=31; $i++)
            $daysColumns[] = 'ifnull(' . $i . 'd' . ',0)';

        return  [
            'prisoner_id',
            'month_no',
            'year',
            'hours' => new \yii\db\Expression(join('+', $daysColumns))

        ];
    }
}
