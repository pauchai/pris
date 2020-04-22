<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 1/3/20
 * Time: 9:17 AM
 */

namespace vova07\electricity\models\backend;


use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceAccounting;
use yii\base\Model;

class GenerateTabularDataForm extends Model
{
    public $from_date;
    public $to_date;

    public function rules()
    {
        return [
          [['dateRange'],'required'],
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => \vova07\base\components\DateRangeBehavior::class,
                'attribute' => 'dateRange',
                'dateStartAttribute' => 'from_date',
                'dateEndAttribute' => 'to_date',
                'displayFormat' => 'd-m-Y'


            ]
        ];
    }

    public function generateOrSyncDevicesAccounting()
    {

        foreach (Device::find()->hasPrisoner()->all() as $device)
        {
            if (DeviceAccounting::find()->andWhere([
                'device_id' => $device->primaryKey,
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
            ])->one())
                continue;

            $deviceAccounting  = new DeviceAccounting();
            $deviceAccounting->prisoner_id = $device->prisoner_id;
            $deviceAccounting->device_id = $device->primaryKey;
            $deviceAccounting->from_date = $this->from_date;
            $deviceAccounting->to_date = $this->to_date;

            $deviceAccounting->save();
           // $deviceAccounting->autoCalculation();

        }
    }
    public function getDeviceAccountingsWithoutPrisoner()
    {
        $res = [];
        foreach (Device::find()->withoutPrisoner()->all() as $device)
        {
            if (DeviceAccounting::find()->andWhere([
                'device_id' => $device->primaryKey,
                'from_date' => $this->from_date,
                'to_date' => $this->to_date,
            ])->one())
                continue;

            $deviceAccounting  = new DeviceAccounting();
            $deviceAccounting->device_id = $device->primaryKey;
            $deviceAccounting->from_date = $this->from_date;
            $deviceAccounting->to_date = $this->to_date;
            $deviceAccounting->status_id = DeviceAccounting::STATUS_INIT;
            $res[] = $deviceAccounting;


        }
        return $res;
    }
}