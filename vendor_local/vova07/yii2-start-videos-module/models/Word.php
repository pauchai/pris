<?php

namespace vova07\videos\models;

use Yii;
use vova07\base\models\ActiveRecordMetaModel;

/**
 * This is the model class for table "words".
 *
 * @property int $id MODEL_ID
 * @property string $title MODEL_TITLE
 * @property string $translation VIDEO_SOURCE_URL
 */
class Word extends ActiveRecordMetaModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'words';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['title', 'translation'], 'string', 'max' => 255],
        ];
    }

    public static function getMetadata()
    {
        $migration = new Migration();
        $metadata = [
            'fields' => [
                'id' => $migration->primaryKey(),
                'text_ru' => $migration->string(),
                'text_eng' => $migration->string(),
            ],


        ];
        return $metadata;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'translation' => 'Translation',
        ];
    }
}
