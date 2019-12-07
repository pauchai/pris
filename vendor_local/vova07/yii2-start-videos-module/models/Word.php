<?php

namespace vova07\videos\models;

use Yii;

/**
 * This is the model class for table "words".
 *
 * @property int $id MODEL_ID
 * @property string $title MODEL_TITLE
 * @property string $translation VIDEO_SOURCE_URL
 */
class Word extends \yii\db\ActiveRecord
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
