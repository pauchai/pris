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
use vova07\users\models\Person;
use vova07\users\models\Prisoner;

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
       return Prisoner::find()->andWhere([
          "__person_id" => Document::find()->select("person_id")->distinct()->andWhere([
              'type_id'=>$availableDocuments,
              'country_id' => Country::findOne(['iso' =>Country::ISO_MOLDOVA])->primaryKey
          ])


        ])->active();
    }
    static public function getPrisonerWithExpiredDocumentsQuery()
    {
        $availableDocuments = [
            Document::TYPE_ID, Document::TYPE_ID_PROV, Document::TYPE_F9
        ];
        return Prisoner::find()->andWhere([
            "__person_id" => Document::find()->select("person_id")->distinct()->andWhere(
                [
                    'type_id'=>$availableDocuments,
                    'country_id' => Country::findOne(['iso' =>Country::ISO_MOLDOVA])->primaryKey
                ]
            )->expired()

        ])->active();
    }

    static public function getPrisonersWithForeignersAndStatLessQuery()
    {
        $sub =  Person::find()->select('__ident_id')->stateless();
        return Prisoner::find()->orWhere([
            '__person_id' => Person::find()->select('__ident_id')->stateless()
        ])->orWhere([
            '__person_id' => Person::find()->select('__ident_id')->foreigners()
        ]);
    }

    static public function getPrisonersWithForeignPassport()
    {
        $sub = Document::find()->foreigners()->select('person_id');
        return Prisoner::find()->andWhere([
            '__person_id' => $sub
        ]);
    }
    static public function getPrisonersWithDocumentInProcess()
    {
        $sub = Document::find()->inProcess()->select('person_id');
        return Prisoner::find()->andWhere([
            '__person_id' => $sub
        ]);
    }


}