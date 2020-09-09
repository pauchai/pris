<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/7/20
 * Time: 8:42 AM
 */

namespace vova07\documents\models\backend;



use vova07\documents\helpers\ReportHelper;
use vova07\documents\Module;
use vova07\users\models\Prisoner;
use yii\base\Model;

class Report2Model extends Model
{



    public function getActivePrisoners(){
         return Prisoner::find()->active();
    }
    public function getPrisonersWithLegalDocuments(){
        return ReportHelper::getPrisonerWithLegalDocumentsQuery();
    }
    public function getPrisonersWithExpiredDocuments(){
        return ReportHelper::getPrisonerWithExpiredDocumentsQuery();
    }
    public function getPrisonersForeignersAndStateless()
    {
        return ReportHelper::getPrisonersWithForeignersAndStatLessQuery();
    }
    public function getPrisonersLocalWithPassport()
    {
        return ReportHelper::getPrisonersLocalWithPassport();
    }
    public function getPrisonersWithDocumentInProcess()
    {
        return ReportHelper::getPrisonersWithDocumentInProcess();
    }

    public function getPrisonersWithNotEnoughBalanceForDocument()
    {
        return ReportHelper::getPrisonersWithNotEnoughBalanceForDocument();
    }

    public function attributeLabels()
    {
        return [
            'activePrisoners' => Module::t('labels' , 'LABEL_ACTIVE_PRISONERS'),
            'prisonersWithLegalDocuments' => Module::t('labels', "LABEL_PRISONERS_WITH_LEGAL_DOCUMENTS"),
            'prisonersWithExpiredDocuments' => Module::t('labels', "LABEL_PRISONERS_WITH_EXPIRED_DOCUMENTS"),
            'prisonersForeignersAndStateless' => Module::t('labels', "LABEL_PRISONERS_FOREIGNERS_AND_STATELESS"),
            'prisonersLocalWithPassport' => Module::t('labels', "LABEL_PRISONERS_LOCAL_WITH_PASSPORT"),
            'prisonersWithDocumentInProcess' => Module::t('labels', "LABEL_PRISONERS_WITH_DOCUMENTS_IN_PROCESS"),
            'prisonersWithNotEnoughBalanceForDocument' => Module::t('labels', "LABEL_PRISONERS_WITH_NOT_ENOUGH_BALANCE_FOR_DOCUMENT"),


        ];
    }


}