<?php

use yii\db\Migration;
use vova07\concepts\models\Concept;
use vova07\concepts\models\ConceptDict;

/**
 * Class m210713_081933_dbConceptsAddConceptDictImportToNewSchema
 */
class m210713_081933_dbConceptsAddConceptDictImportToNewSchema extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach(Concept::find()->all() as $concept){
            if (!($conceptDict = \vova07\concepts\models\ConceptDict::findOne(['title' => $concept->title]))){
                $conceptDict = new ConceptDict();
                $conceptDict->title = $concept->title;
                $conceptDict->save();

            }
            $concept->dict_id = $conceptDict->primaryKey;
            $concept->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach(ConceptDict::find()->all() as $conceptDict){
            $conceptDict->delete();
        }
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210713_081933_dbConceptsAddConceptDictImportToNewSchema cannot be reverted.\n";

        return false;
    }
    */
}
