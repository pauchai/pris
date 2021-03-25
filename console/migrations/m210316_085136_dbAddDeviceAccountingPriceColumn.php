<?php

use yii\db\Migration;
use vova07\electricity\models\DeviceAccounting;

/**
 * Class m210316_085136_dbAddDeviceAccountingPriceColumn
 */
class m210316_085136_dbAddDeviceAccountingPriceColumn extends Migration
{

    const PRICE_KILOWATT_HOUR_OLD = 1.92;
    const PRICE_KILOWATT_HOUR_NEW = 1.70;
    const DATE_LIMIT = '2020-08-01';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(DeviceAccounting::tableName(),  'price',DeviceAccounting::getMetadata()['fields']['price']);
          $this->setValue();
    }
    private function setValue()
    {


        Yii::$app->db->createCommand('UPDATE device_accountings SET price = value * :price WHERE from_date < UNIX_TIMESTAMP(:date_limit)',
            [
                ':price' => self::PRICE_KILOWATT_HOUR_OLD,
                ':date_limit' => self::DATE_LIMIT
            ])->execute();
        Yii::$app->db->createCommand('UPDATE device_accountings SET price = value * :price WHERE from_date >= UNIX_TIMESTAMP(:date_limit)',
            [
                ':price' => self::PRICE_KILOWATT_HOUR_NEW,
                ':date_limit' => self::DATE_LIMIT
            ])->execute();

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(DeviceAccounting::tableName(), 'price');


        return true;
    }
}
