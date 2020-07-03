<?php

use yii\db\Migration;
use vova07\salary\models\backend\BalanceByOfficerView;
use vova07\salary\models\Balance;
use yii\db\Query;

/**
 * Class m200703_064814_vb_balance_by_officer
 */
class m200703_064814_vb_balance_by_officer extends Migration
{
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
        return $this->db->createCommand()->dropView(BalanceByOfficerView::tableName())->execute();
    }


    public function createView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(BalanceByOfficerView::tableName()));
        $this->db->createCommand()->createView(BalanceByOfficerView::tableName(),
            (new Query())->select(
                [
                    'officer_id',
                    'debit' => new \yii\db\Expression('ROUND(SUM(CASE WHEN amount>=0 THEN amount END),2)'),
                    'credit' => new \yii\db\Expression('ROUND(SUM(CASE WHEN amount<0 THEN -1 * amount END),2)'),
                    'remain' => new \yii\db\Expression('ROUND(SUM(amount),2)'),

                ]
            )->from(Balance::tableName())->groupBy(['officer_id'])
        )->execute();
        $this->endCommand($time);
    }
}
