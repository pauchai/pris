<?php

use yii\db\Migration;

/**
 * Class m210406_113627_personDecoupleIdent
 */
class m210406_113627_personDecoupleIdentChangePrimary extends Migration
{
    const FK_NAME = "fk_person1726261336"; //fk to ident
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->db->createCommand()->checkIntegrity(false);

        //$this->db->createCommand()->dropForeignKey(self::FK_NAME, \vova07\users\models\Person::tableName() );
        $this->db->createCommand()
            ->dropPrimaryKey('PRIMARY', "person")
            ->execute();
        $this->db->createCommand()
            ->addPrimaryKey('__ownableitem_id', "person", "__ownableitem_id")
            ->execute();

        $this->db->createCommand()->checkIntegrity();
    }
    private function reLinkChildsToPersonWithNewPk()
    {
        $tableSchemas = $this->db->getSchema()->getTableSchemas();
        $log = "";
        foreach ($tableSchemas as $tableSchema) {
            /**
             * @var \yii\db\TableSchema tableSchema
             */
            if ($tableSchema->foreignKeys)
                foreach ($tableSchema->foreignKeys as $foreignKey){
                    $foreignKeyDub = $foreignKey ;
                    $referencedSchemaName = $foreignKeyDub[0];
                    unset($foreignKeyDub[0]);
                    $keys = array_keys($foreignKeyDub);

                    if ($referencedSchemaName == "person"){

                        $log = $referencedSchemaName ;
                    }

                }

        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->db->createCommand()->checkIntegrity(false);

        $this->db->createCommand()
            ->dropPrimaryKey('PRIMARY', "person")
            ->execute();
        $this->db->createCommand()
            ->addPrimaryKey('__ident_id', "person", "__ident_id")
            ->execute();
        $this->db->createCommand()->checkIntegrity();

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210406_113627_personDecoupleIdent cannot be reverted.\n";

        return false;
    }
    */
}
