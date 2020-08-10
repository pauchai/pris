<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 7/9/20
 * Time: 8:21 AM
 */

namespace vova07\videos\models\metadata;



use Done\Subtitles\Subtitles;
use vova07\videos\Module;
use yii\validators\FileValidator;
use yii\web\UploadedFile;
use yii\base\Model;



class SubTitle extends Model
{
    const UPLOADED_EXTENSIONS = ['srt', 'vtt'];
    const CONVERT_EXTENSION = 'vtt';
    public $name;
    public $type;
    public $filename;

    /**
     * @var $file UploadedFile
     */
    public $file;

        public function rules()
        {
           return
               [
                   [['filename'],'string'],

                   [['file'], FileValidator::class, 'extensions' => self::UPLOADED_EXTENSIONS]

           ];
        }

        public function upload()
        {

            $newFileName =  $this->file->baseName . '.' . $this->file->extension;

            if ($this->validate()){
                $this->file->saveAs(Module::getInstance()->subTitlesBasePath . '/' . $this->file->baseName . '.' . $this->file->extension);
                if ($this->file->extension <> 'vtt'){
                    Subtitles::convert(
                        Module::getInstance()->subTitlesBasePath . '/' . $this->file->baseName . '.' . $this->file->extension,
                        Module::getInstance()->subTitlesBasePath . '/' . $this->file->baseName . '.' . self::CONVERT_EXTENSION

                    );
                }
                unlink(Module::getInstance()->subTitlesBasePath . '/' . $this->file->baseName . '.' . $this->file->extension);
                $this->type = self::CONVERT_EXTENSION;
                $this->filename = $this->file->baseName . '.' . self::CONVERT_EXTENSION;
                return true;
            } else
                return false;



        }
}