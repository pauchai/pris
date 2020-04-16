<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\plans\models\backend;




use vova07\base\components\DateConvertJuiBehavior;
use vova07\plans\components\SummarizedDataProvider;
use vova07\plans\models\ProgramPrisoner;
use vova07\plans\models\SummarizedModel;
use vova07\users\helpers\UserHelper;
use vova07\users\models\backend\PrisonerSearch;
use vova07\users\models\Prisoner;
use vova07\users\models\PrisonerQuery;
use vova07\users\models\PrisonerView;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\helpers\ArrayHelper;


class SummarizedProgramsSearch extends  PrisonerView
{
    public function rules()
    {
        return [
            [['fio'],'string']
        ];
    }

    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $dataProvider->sort->defaultOrder = ['fio' => SORT_ASC];
        if ($this->load($params) && $this->validate()) {
            $query->andFilterWhere(
                ['like', 'fio', $this->fio]
            );
        }
        return $dataProvider;
    }

    public static function find()
    {
        return new PrisonerQuery(get_class());
    }

    /**
     * @return \vova07\plans\models\ProgramPrisonerQuery
     */
    public function getPrisonerProgramsByEducator()
    {

        return $this->getPrisonerProgramsByRoles(SummarizedModel::$educatorRoles);
    }

    /**
     * @return \vova07\plans\models\ProgramPrisonerQuery
     */
    public function getPrisonerProgramsBySociologist()
    {
        return $this->getPrisonerProgramsByRoles(SummarizedModel::$sociologistRoles);
    }

    /**
     * @return \vova07\plans\models\ProgramPrisonerQueryprisons@localhost
     */
    public function getPrisonerProgramsByPsychologist()
    {
        return $this->getPrisonerProgramsByRoles(SummarizedModel::$psychologistRoles);
    }

    /**
     * @param $roles
     * @return \vova07\plans\models\ProgramPrisonerQuery
     */
    public function getPrisonerProgramsByRoles($roles)
    {
        $query = ProgramPrisoner::find()->andWhere(['prisoner_id' => $this->primaryKey]);
        return $query->joinWith('ownableitem o')->andWhere([
            'o.created_by' => UserHelper::getUserIdsByRolesQuery($roles),
        ]);
    }



}
