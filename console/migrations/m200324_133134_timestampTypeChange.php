<?php

use yii\db\Migration;

/**
 * Class m200324_133134_timestampTypeChange
 */
class m200324_133134_timestampTypeChange extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci

        }

        $this->alterColumn(\vova07\documents\models\Document::tableName(),
            'date_expiration',
            \yii\db\Schema::TYPE_BIGINT
            );
        $this->alterColumn(\vova07\documents\models\Document::tableName(),
            'date_issue',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\tasks\models\Committee::tableName(),
            'date_start',
            \yii\db\Schema::TYPE_BIGINT
        );
        $this->alterColumn(\vova07\tasks\models\Committee::tableName(),
            'date_finish',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\concepts\models\ConceptClass::tableName(),
            'at',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\electricity\models\DeviceAccounting::tableName(),
            'from_date',
            \yii\db\Schema::TYPE_BIGINT
        );
        $this->alterColumn(\vova07\electricity\models\DeviceAccounting::tableName(),
            'to_date',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\events\models\Event::tableName(),
            'date_start',
            \yii\db\Schema::TYPE_BIGINT
        );
        $this->alterColumn(\vova07\events\models\Event::tableName(),
            'date_finish',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\humanitarians\models\HumanitarianIssue::tableName(),
            'date_issue',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\base\models\Item::tableName(),
            'created_at',
            \yii\db\Schema::TYPE_BIGINT
        );
        $this->alterColumn(\vova07\base\models\Item::tableName(),
            'updated_at',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\jobs\models\JobPaidList::tableName(),
            'assigned_at',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\users\models\PrisonerLocationJournal::tableName(),
            'at',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\prisons\models\PrisonerSecurity::tableName(),
            'date_start',
            \yii\db\Schema::TYPE_BIGINT
        );
        $this->alterColumn(\vova07\prisons\models\PrisonerSecurity::tableName(),
            'date_end',
            \yii\db\Schema::TYPE_BIGINT
        );

        $this->alterColumn(\vova07\plans\models\ProgramPrisoner::tableName(),
            'date_plan',
            \yii\db\Schema::TYPE_BIGINT
        );
        $this->alterColumn(\vova07\plans\models\ProgramPrisoner::tableName(),
            'finished_at',
            \yii\db\Schema::TYPE_BIGINT
        );

    }

    public function down()
    {
       return true;
    }
}
