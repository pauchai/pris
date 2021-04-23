<?php

use yii\db\Migration;
use vova07\socio\models\MaritalStatus;
use \vova07\socio\Module;

/**
 * Class m210421_131135_dbgenerateMaritalStatusRows
 */
class m210421_131135_dbgenerateMaritalStatusRows extends Migration
{
    private function  getRows(){
      return [
        MaritalStatus::STATUS_MARRIAGE => Module::t('default', 'STATUS_MARRIAGE'),
        MaritalStatus::STATUS_DIVORCE => Module::t('default', 'STATUS_DIVORCE'),
          MaritalStatus::STATUS_NEVER_MARRIAGE => Module::t('default', 'STATUS_NEVER_MARRIAGE'),
          MaritalStatus::STATUS_COHABITER => Module::t('default', 'STATUS_COHABITER'),
          MaritalStatus::STATUS_WIDOWER => Module::t('default', 'STATUS_WIDOWER'),

    ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach ($this->getRows() as $id => $title)
        {
            $status = new MaritalStatus(compact('id', 'title'));
            $status->save();

        }
    }

    /**
     * {@inheritdoc}
     */
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
        echo "m210421_131135_dbgenerateMaritalStatusRows cannot be reverted.\n";

        return false;
    }
    */
}
