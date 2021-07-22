<?php

use yii\db\Migration;
use vova07\concepts\models\Concept;
/**
 * Class m210713_083615_dbConceptsAddConceptDictDropUnusedColumns
 */
class m210713_083615_dbConceptsAddConceptDictDropUnusedColumns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn(Concept::tableName(), 'title');
        $this->dropColumn(Concept::tableName(), 'slug');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn(Concept::tableName(), 'title', $this->string()->notNull());
        $this->addColumn(Concept::tableName(), 'slug', $this->string()->notNull());
        foreach (Concept::find()->all() as $concept)
        {
            $concept->title = $concept->dict->title;
            $concept->slug = $concept->dict->slug;
            $concept->save();
        }
        return true ;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210713_083615_dbConceptsAddConceptDictDropUnusedColumns cannot be reverted.\n";

        return false;
    }
    */
}
