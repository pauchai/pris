<?php

use yii\db\Migration;
use vova07\users\models\PersonView;
use vova07\users\models\Person;

/**
 * Class m210415_060111_dbModifyPersonView
 */
class m210415_060111_dbModifyPersonView extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PersonView::tableName()));
        $this->db->createCommand()->dropView(PersonView::tableName())->execute();

        $this->db->createCommand()->createView(PersonView  ::tableName(),
            (Person::find())->select(
                [

                    'person.*',
                    'fio' => new \yii\db\Expression("concat(person.second_name, ' ', person.first_name, ' ', person.patronymic)")



                ]
            )
        )->execute();
        $this->endCommand($time);
    }
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
        echo "m210415_060111_dbModifyPersonView cannot be reverted.\n";

        return false;
    }
    */
}
