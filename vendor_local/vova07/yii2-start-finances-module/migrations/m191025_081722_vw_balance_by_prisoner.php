<?php

use yii\db\Migration;
use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\finances\models\Balance;
use yii\db\Query;
/**
 * Class m191025_081722_vw_balance_by_prisoner
 */
class m191025_081722_vw_balance_by_prisoner extends Migration
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
          return $this->db->createCommand()->dropView(BalanceByPrisonerView::tableName())->execute();
    }


    public function createView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(BalanceByPrisonerView::tableName()));
        $this->db->createCommand()->createView(BalanceByPrisonerView::tableName(),
            (new Query())->select(
                [
                    'prisoner_id',
                    'debit' => new \yii\db\Expression('ROUND(SUM(CASE WHEN type_id=' . Balance::TYPE_DEBIT. ' THEN amount END),2)'),
                    'credit' => new \yii\db\Expression('ROUND(SUM(CASE WHEN type_id=' . Balance::TYPE_CREDIT. ' THEN amount END),2)'),
                    'remain' => new \yii\db\Expression('ROUND(IFNULL(SUM(CASE WHEN type_id=' . Balance::TYPE_DEBIT. ' THEN amount END),0),2) - ROUND(IFNULL(SUM(CASE WHEN type_id=' . Balance::TYPE_CREDIT. ' THEN amount END),0),2)'),

                ]
            )->from(Balance::tableName())->groupBy(['prisoner_id'])
        )->execute();
        $this->endCommand($time);
    }

}
