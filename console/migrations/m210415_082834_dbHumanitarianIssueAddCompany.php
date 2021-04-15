<?php

use yii\db\Migration;
use vova07\humanitarians\models\HumanitarianIssue;
use vova07\prisons\models\Company;
/**
 * Class m210415_082834_dbHumanitarianIssueAddCompany
 */
class m210415_082834_dbHumanitarianIssueAddCompany extends Migration
{

    const FK_HUMANITARIAN_ISSUE_COMPANY = 'fk_humanitaria_issue_company';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {


        $this->addColumn(HumanitarianIssue::tableName(), 'company_id', HumanitarianIssue::getMetadata()['fields']['company_id']);
        $this->addForeignKey(self::FK_HUMANITARIAN_ISSUE_COMPANY, HumanitarianIssue::tableName(), ['company_id'], Company::tableName(), ['__ownableitem_id']);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_HUMANITARIAN_ISSUE_COMPANY, HumanitarianIssue::tableName());
        $this->dropColumn(HumanitarianIssue::tableName(), 'company_id');

        return true;
    }
}