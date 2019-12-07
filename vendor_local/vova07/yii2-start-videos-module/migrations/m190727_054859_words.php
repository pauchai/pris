<?php

use yii\db\Migration;

/**
 * Class m190727_054859_words
 */
class m190727_054859_words extends Migration
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

        $this->createTable("words",[
            'id' => $this->primaryKey()->comment("MODEL_ID"),
            "title" => $this->string()->notNull()->comment("MODEL_TITLE"),
            "translation" => $this->string()->comment("VIDEO_SOURCE_URL"),
        ], $tableOptions);


        $this->createIndex("title_idx", "{{%words}}", "title");


        $this->createTable("video_word",[
            'video_id' => $this->integer()->notNull()->comment("VIDEO_ID"),
            "word_id" => $this->integer()->notNull()->comment("WORD_ID"),

        ], $tableOptions);

        $this->createIndex("video_id_word_id_idx", "{{%video_word}}", ["video_id","word_id"], true);
        $this->addForeignKey("video_word_video_id_videos_id_fk", "video_word", "video_id", \vova07\videos\models\Video::tableName(),'id');
        $this->addForeignKey("video_word_word_id_videos_id_fk", "video_word", "word_id", \vova07\videos\models\Word::tableName(),'id');


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('video_word');
        $this->dropTable('words');
        return true ;

    }
}
