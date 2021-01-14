<?php
namespace vova07\users\models\backend;
use vova07\prisons\models\Sector;
use vova07\users\models\Prisoner;
use vova07\users\models\PrisonerLocationJournal;
use vova07\users\models\PrisonerLocationJournalWithNextView;
use yii\data\ActiveDataProvider;
use yii\db\Expression;


/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/1/19
 * Time: 11:30 AM
 */

class PrisonerLocationJournalWithNextViewSearch extends PrisonerLocationJournalWithNextView
{
    public $year;

    public function rules()
    {
        return [

            [['sector_id', 'year'],'safe'],
            [['year'], 'default', 'value' => date('Y')],


        ];
    }

    public function search($params)
    {
        $query = self::find()->andWhere(['status_id' => Prisoner::STATUS_ACTIVE])->orderBy(PrisonerLocationJournalWithNextView::tableName().'.sector_id ASC' );
        $query->joinWith('person');

        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        $this->load($params);
        $this->validate();

        $query->andFilterWhere([PrisonerLocationJournalWithNextView::tableName().'.sector_id' => $this->sector_id]);
        $query->andWhere(new Expression("YEAR(DATE_ADD(FROM_UNIXTIME(0), INTERVAL at SECOND)) = :year", [':year' => $this->year]));

        $dataProvider->sort->attributes['prisoner.person.fio'] = [
            'asc' => ['person.second_name' => SORT_ASC, 'person.first_name' => SORT_ASC],
            'desc' => ['person.second_name' => SORT_DESC, 'person.first_name' => SORT_DESC],
        ];
        return $dataProvider;

    }

}