<?php

use yii\db\Migration;
use vova07\users\models\Person;
use vova07\users\models\Ident;

/**
 * Class m210407_155244_personDecoupleIdentMovePersonToIdent_deleteIdent_id
 */
class m210407_155244_personDecoupleIdentMovePersonToIdent_deleteIdent_id extends Migration
{

    const FK_PERSON_IDENT_NAME  = "fk_person1726261336";
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand()->dropForeignKey(self::FK_PERSON_IDENT_NAME, Person::tableName(), '__ident_id')->execute();
        $this->db->createCommand()->dropColumn( Person::tableName(), '__ident_id')->execute();


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->db->createCommand()->addColumn( Person::tableName(), '__ident_id', 'integer')->execute();
        $this->db->createCommand()->addForeignKey(self::FK_PERSON_IDENT_NAME, Person::tableName(), '__ident_id', Ident::tableName(), '__item_id')->execute();


        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210407_155244_personDecoupleIdentMovePersonToIdent_deleteIdent_id cannot be reverted.\n";

        return false;
    }
    */
}
