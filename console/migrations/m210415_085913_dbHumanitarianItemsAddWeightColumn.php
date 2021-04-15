<?php

use yii\db\Migration;
use vova07\humanitarians\models\HumanitarianItem;

/**
 * Class m210415_085913_dbHumanitarianItemsAddWeightColumn
 */
class m210415_085913_dbHumanitarianItemsAddWeightColumn extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(HumanitarianItem::tableName(),  'sort_weight',HumanitarianItem::getMetadata()['fields']['sort_weight']);
        foreach (HumanitarianItem::find()->all() as $item){
            $item->sort_weight = 0;
            $item->save();
        }



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(HumanitarianItem::tableName(), 'sort_weight');

        return true;
    }
}
