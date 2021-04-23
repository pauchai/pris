<?php

use yii\db\Migration;
use \vova07\socio\models\RelationMaritalView;
use \vova07\users\models\PrisonerView;
use vova07\socio\models\Relation;
use vova07\socio\models\MaritalState;
use yii\db\Query;
use yii\db\Expression;

/**
 * Class m210421_132401_dbRelationMaritalView
 */
class m210421_132401_dbRelationMaritalView extends Migration
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
        if ($this->db->getTableSchema(RelationMaritalView::tableName()))
            return $this->db->createCommand()->dropView(RelationMaritalView::tableName())->execute();
    }


    public function createView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(RelationMaritalView::tableName()));
        $this->db->createCommand()->createView(RelationMaritalView::tableName(),
            //person_id as officer_id, op.company_id,op.division_id, op.postdict_id, op.full_time, op.benefit_class, op.title
            //officer_posts as op ON officer_id = __person_id;
            PrisonerView::find()->select(
                [
                    PrisonerView::tableName().'.*',
                    //'vw_prisoner.__person_id',
                    'ref_person_id',
                    'marital_status_id',
                    'relation_type_id'

                ])->active()
                ->leftJoin([
                    'u' => (new Query)
                    ->select([
                        'person_id',
                        'ref_person_id',
                        'marital_status_id' => new Expression('min(case when category = "marital_state" then status_id end)'),
                        'relation_type_id' => new Expression('min(case when category = "relation" then status_id end)'),
                      ])->from([
                          'm' => MaritalState::find()->select([
                            'person_id' => '__person_id',
                            'ref_person_id',
                            'category' => new Expression('"marital_state"'),
                            'status_id'
                        ])
                        ->union(
                            Relation::find()->select([
                                'person_id',
                                'ref_person_id',
                                new Expression('"relation"'),
                                'type_id'
                            ])
                        )
                    ])->groupBy(['person_id' , 'ref_person_id'])
                ],
                    'vw_prisoner.__person_id=person_id')


        )->execute();
        $this->endCommand($time);
    }
}
