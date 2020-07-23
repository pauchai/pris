<?php

use yii\db\Migration;
use vova07\prisons\models\Rank;

/**
 * Class m200722_075911_dbAddCategoryRankColumn
 */
class m200722_075911_dbAddCategoryRankColumn extends Migration
{
    const CATEGORY_RANKS = [
        Rank::CATEGORY_ID_OFFICER  => [
            Rank::RANK_CHESTOR_DE_JUSTITIE,
            Rank::RANK_COMISAR_SEF_DE_JUSTITIE,
            Rank::RANK_COMISAR_PRINCIPAL_DE_JUSTITIE,
            Rank::RANK_COMISAR_DE_JUSTITIE,
            Rank::RANK_INSPECTOR_PRINCIPAL_DE_JUSTITIE,
            Rank::RANK_INSPECTOR_SUPERIOR_DE_JUSTITIE,
            Rank::RANK_INSPECTOR_DE_JUSTITIE,

        ],
        Rank::CATEGORY_ID_SUB_OFFICER => [
            Rank::RANK_AGENT_SEF_PRINCIPAL_DE_JUSTITIE,
            Rank::RANK_AGENT_SEF_DE_JUSTITIE,
            Rank::RANK_AGENT_SEF_ADJUNCT_DE_JUSTITIE,
            Rank::RANK_AGENT_PRINCIPAL_DE_JUSTITIE,
            Rank::RANK_AGENT_SUPERIOR_DE_JUSTITIE,
        ],
        Rank::CATEGORY_ID_CIVIL => [
            Rank::RANK_CIVIL,

        ],

    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(Rank::tableName(),  'category_id',Rank::getMetadata()['fields']['category_id']);
        $this->alterColumn(Rank::tableName(),  'rate',Rank::getMetadata()['fields']['rate']);
        $this->addRankCivil();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Rank::tableName(), 'category_id');
        $this->dropRankCivil();
        return true;
    }

    public function addRankCivil()
    {
        $rank = new Rank([
            'id' => Rank::RANK_CIVIL,
            'title' => 'RANK_CIVIL',
            'rate' => 0,
            'category_id' => Rank::CATEGORY_ID_CIVIL
        ]);
        return $rank->save();
    }
    public function dropRankCivil()
    {
       return Rank::findOne(Rank::RANK_CIVIL)->delete();

    }


}
