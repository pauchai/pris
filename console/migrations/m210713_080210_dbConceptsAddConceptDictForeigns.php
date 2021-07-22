<?php

use yii\db\Migration;
use vova07\concepts\models\Concept;
use vova07\concepts\models\ConceptDict;

/**
 * Class m210713_080210_dbConceptsAddConceptDictForeigns
 */
class m210713_080210_dbConceptsAddConceptDictForeigns extends Migration
{
    const FK_DICT_ID = 'fk_concept_dict_id';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Concept::tableName(),'dict_id', $this->integer());
        $this->addForeignKey(self::FK_DICT_ID,Concept::tableName(),'dict_id', ConceptDict::tableName(),ConceptDict::primaryKey());

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_DICT_ID,Concept::tableName());
        $this->dropColumn(Concept::tableName(),'dict_id');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210713_080210_dbConceptsAddConceptDictForeigns cannot be reverted.\n";

        return false;
    }
    */
}
