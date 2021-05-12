<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 6/15/19
 * Time: 6:10 PM
 */

namespace vova07\socio\models\backend;




use vova07\documents\models\Document;
use vova07\socio\models\RelationMaritalView;
use vova07\socio\models\RelationMaritalViewQuery;

class RelationMaritalViewSearch   extends  RelationMaritalView
{
    public $metaStatusId;
    public function rules()
    {
        return [
           [['fio'], 'safe'],
            [['metaStatusId'], 'safe'],

        ];
    }
    public function search($params)
    {
        /**
         * @var $query RelationMaritalViewQuery
         */
        $query = self::find();
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query
        ]);
        if ($this->validate() && $this->load($params)){


            if ($this->metaStatusId) {


                if ($this->metaStatusId == Document::META_STATUS_ABOUT_EXPIRATION)
                    $query->mergeDocumentQuery(Document::find()->aboutExpiration());
                    //$query->aboutExpiration();
                elseif ($this->metaStatusId == Document::META_STATUS_EXPIRATED)
                    $query->mergeDocumentQuery(Document::find()->active()->expired());
                    //$query->expired();
                elseif ($this->metaStatusId == Document::META_STATUS_NOT_EXPIRED){
                    $query->mergeDocumentQuery(Document::find()->active()->notExpired());

                   // $query->documentActive()->notExpired();
                }



            }


            $query->andFilterWhere(['like', 'fio', $this->fio]);
        };



        $dataProvider->sort = [
            'defaultOrder' => [
                'fio' => SORT_ASC,
                '__person_id' => SORT_ASC
            ]
        ];




        return $dataProvider;

    }



}
