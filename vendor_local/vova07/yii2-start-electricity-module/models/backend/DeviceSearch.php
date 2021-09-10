<?php
namespace vova07\electricity\models\backend;
use vova07\electricity\models\Device;
use vova07\electricity\models\DeviceQuery;
use yii\data\ActiveDataProvider;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class DeviceSearch extends \vova07\electricity\models\Device
{
    public function rules()
    {
        return [

            [['prisoner_id','sector_id','cell_id', 'status_id'],'integer'],
            [['title'], 'string'],
            [['status_id'], 'default', 'value' => Device::STATUS_ID_ACTIVE]

        ];


    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query =  self::find()->joinWith([
            'person' => function($query){
                return $query->from(['person' => 'person']);
            }
        ]);
        $query->activePrisoners();
        $query->withoutPrisoner(true);
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->query->orderBy([
            'person.second_name' => SORT_ASC,
        ]);
        $this->load($params);
        if ( $this->validate() ){
            $dataProvider->query->andFilterWhere(
                [
                    'prisoner_id' => $this->prisoner_id,
                    'status_id' => $this->status_id,
//
                ]);
            $dataProvider->query->andFilterWhere(
                ['like', 'title', $this->title]);
        }



        return $dataProvider;

    }

}