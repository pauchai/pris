<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/5/20
 * Time: 4:35 PM
 */

namespace vova07\videos\models;


use yii\base\Model;

class VideoWordForm extends Model
{
    public $video_id;
    public $title;


    public function rules()
    {
        return [
          [['video_id', 'title'], 'required'],
          [['title'], 'string'],
           [['video_id'], 'integer']
        ];
    }
    public function getVideo()
    {
        return Video::findOne($this->video_id);
    }

    public function saveWord()
    {
        if (is_null($word = Word::findOne(['title' => $this->title])))
        {
            $word = new Word;
            $word->title = $this->title;
            $word->save();
        }
        $this->getVideo()->link('words', $word);
    }
}