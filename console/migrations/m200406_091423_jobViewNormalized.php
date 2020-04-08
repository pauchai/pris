<?php

use yii\db\Migration;
use vova07\jobs\models\JobPaidNormalizedViewDays;
use vova07\jobs\models\JobNormalizedViewDays;


/**
 * Class m200406_091423_jobViewNormalized
 */
class m200406_091423_jobViewNormalized extends Migration
{
    public function safeUp()
    {
        $this->createView();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        //echo "m200406_091423_jobViewNormalized cannot be reverted.\n";
        //return true;
        return $this->db->createCommand()->dropView(JobNormalizedViewDays::tableName())->execute() ;

    }

    public function createView()
    {

        $time = $this->beginCommand("create view " . $this->db->quoteTableName(JobNormalizedViewDays::tableName()));

        $sqls = [];
        for ($i = 1 ; $i<=31; $i++){
            $dateCompose = "date(concat(year,'-', month_no, '-', '" . $i . "'))";
            $sqls[] = "(SELECT prisoner_id, prison_id, type_id, $dateCompose as at, " . $i . "d as hours, " . JobNormalizedViewDays::CATEGORY_PAID . " as category_id FROM " . \vova07\jobs\models\JobPaid::tableName() . " WHERE not isnull($dateCompose))";
            $sqls[] = "(SELECT prisoner_id, prison_id, type_id, $dateCompose as at, " . $i . "d as hours, " . JobNormalizedViewDays::CATEGORY_NOT_PAID. " as category_id FROM " . \vova07\jobs\models\JobNotPaid::tableName() . " WHERE not isnull($dateCompose))";


        }
        $sql = join('UNION', $sqls);


        $this->db->createCommand()->createView(JobNormalizedViewDays::tableName(),
            $sql
        )->execute();
        $this->endCommand($time);
    }


}
