<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 4/27/20
 * Time: 2:44 PM
 */

namespace vova07\electricity\models\backend;


use DeepCopyTest\Matcher\Y;
use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceAccounting;
use vova07\electricity\models\DeviceQuery;
use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\db\Expression;

class CalendarSearch extends Model
{
    public $year;


    public function rules()
    {
        return [
            [['year'],'integer'],
            [['year'],'default', 'value' => date('Y')]
        ];
    }

    public function search($params)
    {
        $searchModel = $this;
        $searchModel->load($params);
        $searchModel->validate();
        $dataProvider = new ArrayDataProvider(
            [
                'modelClass' => CalendarSearch::class,
                'allModels' => array_map(
                    function($value)use ($searchModel){
                        return ['year' => $searchModel->year, 'month' => $value];
                    }, range(1, 12)
                )
            ]
        );
        return $dataProvider;
    }

    public function getDevices(){
        $query = Device::find();
        $query->andWhere(
            new Expression('isnull(prisoner_id)')
        );
        return $query->all();
    }







}