<?php

use yii\db\Migration;
use vova07\users\models\Person;
use vova07\users\models\PersonView;

/**
 * Class m200928_080338_dbCreateViewPersonWithFIO
 */
class m200928_080338_dbCreateViewPersonWithFIO extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createPersonView();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->db->createCommand()->dropView(PersonView::tableName())->execute();
    }


    public function createPersonView()
    {
        $time = $this->beginCommand("create view " . $this->db->quoteTableName(PersonView::tableName()));

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
}
