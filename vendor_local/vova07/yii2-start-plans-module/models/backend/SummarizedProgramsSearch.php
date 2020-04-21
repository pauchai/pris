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
use vova07\plans\models\ProgramPrisonerQuery;
use vova07\plans\models\SummarizedModel;
use vova07\plans\Module;
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
    const META_STATUS_REALIZED = 1;
    const META_STATUS_NOT_REALIZED = 2;
    const META_STATUS_AMBIGUOUS = 3;
    public $metaStatusId;

    public function rules()
    {
        return [
            [['__person_id', 'sector_id', 'metaStatusId'], 'integer']
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
                    ['__person_id' => $this->__person_id]
                );


            $actualProgramsQuery = ProgramPrisoner::find()->byRoles(array_merge(
                SummarizedModel::$educatorRoles,
                SummarizedModel::$sociologistRoles,
                SummarizedModel::$psychologistRoles
            ));
            if ($this->metaStatusId == self::META_STATUS_REALIZED) {
                $query->andWhere(
                    ['__person_id' => $actualProgramsQuery->realized()->select('prisoner_id')->distinct()]
                );
            }
            if ($this->metaStatusId == self::META_STATUS_NOT_REALIZED) {
                $query->andWhere(
                    ['__person_id' => $actualProgramsQuery->notRealized()->select('prisoner_id')->distinct()]
                );
            }

            if ($this->metaStatusId == self::META_STATUS_AMBIGUOUS) {
                $query->andWhere(
                    ['not in', '__person_id' , $actualProgramsQuery->select('prisoner_id')->distinct()]
                );
            }
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
     * @return \vova07\plans\models\ProgramPrisonerQuery
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
        $query = $this->getPrisonerPrograms();
        //$query = ProgramPrisoner::find()->andFilterWhere(['prisoner_id' => $this->primaryKey]);
        return $query->byRoles($roles);

    }

    public function getPrisonerProgramsActual()
    {
     return    $this->getPrisonerProgramsByRoles(array_merge(
         SummarizedModel::$educatorRoles,
         SummarizedModel::$sociologistRoles,
         SummarizedModel::$psychologistRoles
     ));
    }
    public function getMetaStatusId()
    {
        $baseQuery = $this->getPrisonerProgramsActual();
        $actualProgramsCount =  (clone $baseQuery)->count();
        $actualRealizedProgramsCount = (clone $baseQuery)->realized()->count();
        //$actualNotRealizedProgramsCount = (clone $baseQuery)->notRealized()->count();
        $allProgramsCount =  $this->getPrisonerPrograms()->count();

        if ($actualProgramsCount == $actualRealizedProgramsCount )
            return self::META_STATUS_REALIZED;
        elseif ($allProgramsCount > $actualProgramsCount)
            return self::META_STATUS_AMBIGUOUS;
        else
            return self::META_STATUS_NOT_REALIZED;


    }

    public static function getMetaStatusesForCombo()
    {
        return [
            self::META_STATUS_REALIZED => Module::t('default','META_STATUS_REALIZED_LABEL'),
            self::META_STATUS_NOT_REALIZED => Module::t('default','META_STATUS_NOT_REALIZED_LABEL'),
            self::META_STATUS_AMBIGUOUS => Module::t('default','META_STATUS_AMBIGUOUS_LABEL'),

        ];
    }

    public  function attributes()
    {
        return array_merge(parent::attributes(),[
//           'metaStatusId'
        ]);
    }




}
