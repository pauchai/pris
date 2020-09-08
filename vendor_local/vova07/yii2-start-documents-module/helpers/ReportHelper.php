<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/5/20
 * Time: 8:03 AM
 */

namespace vova07\documents\helpers;


use vova07\countries\models\Country;
use vova07\documents\models\Document;
use vova07\finances\models\backend\BalanceByPrisonerView;
use vova07\users\models\Person;
use vova07\users\models\Prisoner;
use yii\helpers\ArrayHelper;

class ReportHelper
{

    /**
     * @return \vova07\users\models\PrisonerQuery
     */
    static public function getPrisonerWithLegalDocumentsQuery()
    {
        $availableDocuments = [
            Document::TYPE_ID, Document::TYPE_ID_PROV, Document::TYPE_F9
        ];
        $localsPeopleQuery = Person::find()->locals();

        return Prisoner::find()->andWhere([
          "__person_id" => Document::find()->select("person_id")->distinct()->active()->notExpired()
              ->andWhere([
                 'type_id'=>$availableDocuments,
                  'person_id' => $localsPeopleQuery->select(['__ident_id']),


          ]),


        ])->active();
    }
    static public function getPrisonerWithExpiredDocumentsQuery()
    {
        $availableDocuments = [
            Document::TYPE_ID, Document::TYPE_ID_PROV, Document::TYPE_F9, Document::TYPE_TRAVEL_DOCUMENT
        ];
        $localsPeopleQuery = Person::find()->locals();
        return Prisoner::find()->andWhere([
            "__person_id" => Document::find()->select("person_id")->distinct()->andWhere(
                [
                    'type_id'=>$availableDocuments,
                    'person_id' => $localsPeopleQuery->select(['__ident_id']),
                ]


            )->active()->expired()

        ])->active();
    }

    static public function getPrisonersWithForeignersAndStatLessQuery()
    {
        return Prisoner::find()->orWhere([
            '__person_id' => Person::find()->select('__ident_id')->stateless()
        ])->orWhere([
            '__person_id' => Person::find()->select('__ident_id')->foreigners()

        ]);
    }

    static public function getPrisonersLocalWithPassport()
    {
        $sub = Document::find()->andWhere(['type_id' => Document::TYPE_PASSPORT])->select('person_id');
        return Prisoner::find()->andWhere([
            '__person_id' => $sub
        ])->locals()->active();
    }
    static public function getPrisonersWithDocumentInProcess()
    {
        $sub = Document::find()->inProcess()->andWhere(['type_id' => [Document::TYPE_ID, Document::TYPE_ID_PROV]])->select('person_id');
        return Prisoner::find()->andWhere([
            '__person_id' => $sub
        ]);
    }

    static public function getPrisonersWithEnoughBalance()
    {
        $balanceQuery = BalanceByPrisonerView::find()->andWhere(['<','remain', 150]);
        $availableDocuments = [
            Document::TYPE_ID, Document::TYPE_ID_PROV, Document::TYPE_F9, Document::TYPE_TRAVEL_DOCUMENT, Document::TYPE_PASSPORT
        ];
        $localsPeopleIds = ArrayHelper::map(Person::find()->locals()->all(), '__ident_id', '__ident_id');
        $peopleWithBalanceIds = ArrayHelper::map(BalanceByPrisonerView::find()->andWhere(['<','remain', 150])->all(), 'prisoner_id','prisoner_id');
        $ids = array_merge($localsPeopleIds, $peopleWithBalanceIds);

        return Prisoner::find()->andWhere([
            "__person_id" => Document::find()->select("person_id")->distinct()->andWhere(
                [
                    'type_id'=>$availableDocuments,
                    'person_id' => $ids,
                ]


            )->active()->expired()

        ])->active();
    }


}