n<?php

use yii\db\Migration;

/**
 * Class m190716_112236_videos
 */
class m190716_112236_videos extends Migration
{


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;


        // MySql table options
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        }

        $this->createTable("{{%videos}}",[
            'id' => $this->primaryKey()->comment("MODEL_ID"),
            "title" => $this->string()->notNull()->comment("MODEL_TITLE"),
            "source_url" => $this->string()->notNull()->comment("VIDEO_SOURCE_URL"),
            "video_url" => $this->string()->comment("VIDEO_URL"),
            "sub_url" => $this->string()->comment("SUB_URL"),
            "type" => $this->string()->comment("VIDEO_TYPE"),
            'thumbnail_url' => $this->string()->comment("VIDEO_THUMBNAIL_URL"),
            'metadata' => $this->json()->comment("VIDEO_METADATA"),
            'status_id' => $this->integer()->comment("MODEL_STATUS_ID")


        ], $tableOptions);

        $this->createIndex("title_idx", "{{%videos}}", "title");



    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%videos}}');
        return true ;

    }
}
