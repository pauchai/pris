<?php

use yii\db\Migration;
use yii\db\Expression;
use \vova07\users\models\Officer;
use vova07\users\models\OfficerView;
/**
 * Class m200721_221011_createSalaryDbViews
 */
class m200721_221011_createOfficerDbViews extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createOfficerView();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->db->createCommand()->dropView(OfficerView::tableName())->execute();
    }


    public function createOfficerView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(OfficerView::tableName()));
        $csvOfficersRanks = join(',', Officer::CATEGORY_RANKS[Officer::CATEGORY_ID_OFFICER]);
        $csvSubOfficersRanks = join(',', Officer::CATEGORY_RANKS[Officer::CATEGORY_ID_SUB_OFFICER]);
        $categoryOfficer = Officer::CATEGORY_ID_OFFICER;
        $categorySubOfficer = Officer::CATEGORY_ID_SUB_OFFICER;
        $categoryCivil = Officer::CATEGORY_ID_CIVIL;
        $this->db->createCommand()->createView(OfficerView::tableName(),
            (Officer::find())->select(
                [
                    '*',
                    'category_rank_id' => new Expression(
                        " CASE WHEN rank_id in ($csvOfficersRanks) THEN $categoryOfficer ".
                        " WHEN rank_id in ($csvSubOfficersRanks) THEN $categorySubOfficer".
                        " ELSE $categoryCivil END")




                ]
            )
        )->execute();
        $this->endCommand($time);
    }
}
