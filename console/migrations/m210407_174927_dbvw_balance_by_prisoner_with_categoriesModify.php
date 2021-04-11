<?php

use yii\db\Migration;
use vova07\finances\models\BalanceCategory;
use vova07\users\models\PrisonerView;
use vova07\finances\models\backend\BalanceByPrisonerWithCategoryView;
use vova07\finances\models\Balance;
use yii\db\Query;


/**
 * Class m210407_174927_dbvw_balance_by_prisoner_with_categoriesModify
 */
class m210407_174927_dbvw_balance_by_prisoner_with_categoriesModify extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(BalanceByPrisonerWithCategoryView::tableName()));
        $this->db->createCommand()->dropView(BalanceByPrisonerWithCategoryView::tableName())->execute();

        $categories = BalanceCategory::getListForCombo();
        $viewColumns = [
            'prisoner_id',
            'debit' => new \yii\db\Expression('ROUND(SUM(CASE WHEN type_id=' . Balance::TYPE_DEBIT. ' THEN amount END),2)'),
            'credit' => new \yii\db\Expression('ROUND(SUM(CASE WHEN type_id=' . Balance::TYPE_CREDIT. ' THEN amount END),2)'),
            'remain' => new \yii\db\Expression('ROUND(IFNULL(SUM(CASE WHEN type_id=' . Balance::TYPE_DEBIT. ' THEN amount END),0),2) - ROUND(IFNULL(SUM(CASE WHEN type_id=' . Balance::TYPE_CREDIT. ' THEN amount END),0),2)'),
            'fio' => new \yii\db\Expression("concat(person.second_name,' ', person.first_name,' ', person.patronymic)")

        ];
        foreach ($categories as $categoryId=>$categoryTitle)
        {
            $viewColumns['category'.$categoryId] =    new \yii\db\Expression('ROUND(SUM(CASE WHEN category_id=' . $categoryId. ' THEN amount END),2)');

        }
        $this->db->createCommand()->createView(BalanceByPrisonerWithCategoryView::tableName(),
            (new Query())->select(
                $viewColumns
            )->leftJoin('person','person.__ownableitem_id = prisoner_id')->from(Balance::tableName())->groupBy(['prisoner_id'])
        )->execute();
        $this->endCommand($time);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210407_174927_dbvw_balance_by_prisoner_with_categoriesModify cannot be reverted.\n";

        return false;
    }
    */
}
