<?php

use yii\db\Migration;
use vova07\prisons\models\OfficerPostView;
use vova07\prisons\models\OfficerPost;
use vova07\users\models\Officer;
use vova07\users\models\Person;
use yii\db\Query;

/**
 * Class m200709_195001_OfficerPostsView
 */
class m200709_195001_OfficerPostsView extends Migration
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
        return $this->db->createCommand()->dropView(OfficerPostView::tableName())->execute();
    }


    public function createView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(OfficerPostView::tableName()));
        $this->db->createCommand()->createView(OfficerPostView::tableName(),
            //person_id as officer_id, op.company_id,op.division_id, op.postdict_id, op.full_time, op.benefit_class, op.title
            //officer_posts as op ON officer_id = __person_id;
    (new Query())->select(
                [
                    '__person_id as officer_id',
                    'op.company_id',
                    'op.division_id',
                    'op.postdict_id',
                    'op.full_time',
                    'op.benefit_class',
                    'op.title',
                    'p.second_name',
                    'p.first_name',
                    'p.patronymic',
                    'p.birth_year'
                    ]
            )->from(Officer::tableName())->leftJoin(OfficerPost::tableName() . ' op', 'op.officer_id = __person_id')
        ->leftJoin(Person::tableName(). ' p', '__person_id = p.__ident_id')
        )->execute();
        $this->endCommand($time);
    }
}
