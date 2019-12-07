<?php

use yii\db\Migration;
use vova07\finances\models\backend\BalanceByPrisonerWithCategoryView;
use vova07\finances\models\BalanceCategory;
use vova07\finances\models\Balance;
use yii\db\Query;
/**
 * Class m191025_095018_vw_balance_by_prisoner_with_categories
 */
class m191025_095018_vw_balance_by_prisoner_with_categories extends Migration
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
        return $this->db->createCommand()->dropView(BalanceByPrisonerWithCategoryView::tableName())->execute();
    }


    public function createView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(BalanceByPrisonerWithCategoryView::tableName()));
        $categories = BalanceCategory::getListForCombo();
        $viewColumns = [
            'prisoner_id',
            'debit' => new \yii\db\Expression('ROUND(SUM(CASE WHEN type_id=' . Balance::TYPE_DEBIT. ' THEN amount END),2)'),
            'credit' => new \yii\db\Expression('ROUND(SUM(CASE WHEN type_id=' . Balance::TYPE_CREDIT. ' THEN amount END),2)'),
            'remain' => new \yii\db\Expression('ROUND(IFNULL(SUM(CASE WHEN type_id=' . Balance::TYPE_DEBIT. ' THEN amount END),0),2) - ROUND(IFNULL(SUM(CASE WHEN type_id=' . Balance::TYPE_CREDIT. ' THEN amount END),0),2)'),

        ];
        foreach ($categories as $categoryId=>$categoryTitle)
        {
            $viewColumns['category'.$categoryId] =    new \yii\db\Expression('ROUND(SUM(CASE WHEN category_id=' . $categoryId. ' THEN amount END),2)');

        }
        $this->db->createCommand()->createView(BalanceByPrisonerWithCategoryView::tableName(),
            (new Query())->select(
                $viewColumns
            )->from(Balance::tableName())->groupBy(['prisoner_id'])
        )->execute();
        $this->endCommand($time);
    }
}
