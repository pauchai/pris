<?php

use yii\db\Migration;
use vova07\plans\models\PrisonerPlan;
use vova07\users\models\Prisoner;
use vova07\plans\models\PrisonerPlanView;
use vova07\users\models\Person;
use yii\db\Query;

/**
 * Class m210407_175833_dbPrisonerPlanViewModify
 */
class m210407_175833_dbPrisonerPlanViewModify extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PrisonerPlanView::tableName()));
        $this->db->createCommand()->dropView(PrisonerPlanView::tableName())->execute();

        $this->db->createCommand()->createView(PrisonerPlanView::tableName(),
            //person_id as officer_id, op.company_id,op.division_id, op.postdict_id, op.full_time, op.benefit_class, op.title
            //officer_posts as op ON officer_id = __person_id;
            (new Query())->select(
                [
                    'pp.*',
                    'pr.__person_id',
                    'prisoner_status_id' => 'pr.status_id',
                    'fio' => new \yii\db\Expression("concat(p.second_name,' ', p.first_name, ' ', p.patronymic)"),




                ]
            )->from(Prisoner::tableName() . ' pr')->leftJoin(PrisonerPlan::tableName() . ' pp', 'pr.__person_id = pp.__prisoner_id')
                ->leftJoin(Person::tableName() . ' p', 'p.__ownableitem_id = pr.__person_id')
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
        echo "m210407_175833_dbPrisonerPlanViewModify cannot be reverted.\n";

        return false;
    }
    */
}
