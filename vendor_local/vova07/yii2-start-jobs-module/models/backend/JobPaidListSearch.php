<?php
namespace vova07\jobs\models\backend;




class JobPaidListSearch extends \vova07\jobs\models\JobPaidList
{
    public function rules()
    {
        return [

            [['assigned_to'],'safe'],
            [['assignedAtJui'],'date'],
            [['status_id'],'integer'],
            [['deletedAtJui'],'date'],
            ['status_id','default','value'=> self::STATUS_ID_ACTIVE]



        ];


    }

    public function search($params)
    {
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => self::find()->joinWith(['person' => function($query){$query->from('person');}])
        ]);
        $dataProvider->query->orderBy('person.second_name, person.first_name');
        $dataProvider->sort = false;
        $this->load($params);

        if ( $this->validate()){
            $dataProvider->query->andFilterWhere(
                [
                    'assigned_to' => $this->assigned_to,
                    'status_id' => $this->status_id

                ]);
        }
        return $dataProvider;

    }



}