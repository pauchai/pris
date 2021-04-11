<?php

use yii\db\Migration;
use vova07\users\models\Ident;
use vova07\users\models\Person;

/**
 * Class m210407_134558_personDecoupleIdentMovePersonToIdent
 */
class m210407_134558_personDecoupleIdentMovePersonToIdent extends Migration
{

    CONST FK_IDENT_PERSON_NAME = "fk_ident_person";
    CONST FK_PERSON_IDENT_NAME = "fk_ident2774090108";
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();

        $this->db->createCommand()->addColumn(Ident::tableName(), 'person_id', Ident::getMetadata()['fields']['person_id'])->execute();
        $this->db->createCommand()->addForeignKey(self::FK_IDENT_PERSON_NAME ,Ident::tableName(), 'person_id', Person::tableName(), Person::primaryKey()[0])->execute();
        foreach(Person::find()->all() as $person){
            $ident = Ident::findOne($person->__ident_id);
            if ($ident){
                $ident->person_id = $person->primaryKey;
                $ident->save();
            }


        }



        $this->db->createCommand()->checkIntegrity()->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->db->createCommand()->checkIntegrity(false)->execute();


        $this->db->createCommand()->dropForeignKey(self::FK_IDENT_PERSON_NAME ,Ident::tableName(), 'person_id')->execute();
        $this->db->createCommand()->dropcolumn(Ident::tableName(), 'person_id')->execute();


        $this->db->createCommand()->checkIntegrity()->execute();
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210407_134558_personDecoupleIdentMovePersonToIdent cannot be reverted.\n";

        return false;
    }
    */
}
