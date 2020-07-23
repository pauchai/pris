<?php

use yii\db\Migration;
use vova07\users\models\OfficerView;
use vova07\users\models\Officer;

/**
 * Class m200722_085056_dbOfficerView
 */
class m200722_085056_dbOfficerView extends Migration
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

        $this->db->createCommand()->createView(OfficerView::tableName(),
            (Officer::find())->select(
                [
                    'officer.*',
                    'category_rank_id' => 'ranks.category_id'
                ]
            )->joinWith('rank')
        )->execute();
        $this->endCommand($time);
    }
}
