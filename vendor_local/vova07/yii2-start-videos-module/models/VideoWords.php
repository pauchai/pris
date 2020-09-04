<?php

namespace vova07\videos\models;

use Yii;
use vova07\base\models\ActiveRecordMetaModel;
use yii\db\Migration;

class VideoWords extends ActiveRecordMetaModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'video_word';
    }


    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                'video_id' => $migration->integer()->notNull(),
                'word_id' => $migration->string(),

            ],
            'primaries' => [
                [self::class, ['video_id', 'word_id']]
            ],
            'foreignKeys' => [
                [get_called_class(), 'video_id', Video::class, Video::primaryKey()],
                [get_called_class(), 'word_id', Word::class, Word::primaryKey()],



            ],

        ];
        return $metadata;
    }

}
