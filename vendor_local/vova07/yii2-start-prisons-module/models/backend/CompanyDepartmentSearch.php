<?php
namespace vova07\prisons\models\backend;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class CompanyDepartmentSearch extends \vova07\prisons\models\CompanyDepartment
{

    public function search($params)
    {
        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);

        if (!($this->load($params, 'CompanyDepartment') )) {
            return $dataProvider;
         }


        $query->andFilterWhere(
            [
             'company_id' => $this->company_id,
             'department_id' => $this->department_id
            ]
        );
        return $dataProvider;

    }

}