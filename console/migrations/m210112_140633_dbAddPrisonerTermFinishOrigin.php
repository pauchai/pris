<?php

use yii\db\Migration;
use vova07\users\models\Prisoner;
/**
 * Class m210112_140633_dbAddPrisonerTermFinishOrigin
 */
class m210112_140633_dbAddPrisonerTermFinishOrigin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(Prisoner::tableName(),  'term_finish_origin',Prisoner::getMetadata()['fields']['term_finish_origin']);
      //  $this->setValue();
    }

    private function setValue()
    {
        foreach (Prisoner::find()->all() as $prisoner){
            $prisoner->term_finish_origin = $prisoner->term_finish;
            $prisoner->save(false);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Prisoner::tableName(), 'term_finish_origin');


        return true;
    }
}
