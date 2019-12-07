<?php

namespace vova07\site\commands;


use vova07\users\Module;
use Yii;
use yii\console\Controller;
use yii\helpers\BaseFileHelper;

/**
 * Prisons  Images controller.
 */
class ImagesController extends Controller
{


    /**
     * @var boolean $override
     */
    public function actionPhotosToPreviews($override=false)
    {
        $usersModule = Yii::$app->getModule('users');
        $previewPath = Yii::getAlias($usersModule->personPreviewPath);


        $files = BaseFileHelper::findFiles(Yii::getAlias($usersModule->personPreviewPath));

        //$fileName = $files[2];
        //$images = new \Imagick(glob(Yii::getAlias(Module::$piImagesPath) . '/*.jpg'));

            $im  = new \Imagick;
        for ($i=0; $i<count($files); $i++)
        {
            $fileName = $files[$i];

            $fileInfo = pathinfo($fileName);

            $newFileName = $previewPath .  $fileInfo['filename'] . '.' . $fileInfo['extension'];
            echo "file#" . $i . $newFileName . "\n";
            if (file_exists($newFileName) === false || $override) {
                $im->readImage($fileName);


                //list($width, $height) = getimagesize($fileName);
                $width = $im->getImageWidth();
                $height = $im->getImageHeight();
                echo "origin size: $width $height \n";

                $ration = $width / $height;
                $newWidth = $usersModule->personPreviewWidth;
                $newHeight = round($newWidth / $ration);

                echo "new size: $newWidth $newHeight\n";

                $im->resizeImage($newWidth, $newHeight, \Imagick::FILTER_CUBIC, 0.9, false);
                $im->cropThumbnailImage($newWidth,$newWidth);


                //$im->setCompression(\Imagick::COMPRESSION_JPEG);
                //$im->setCompressionQuality(20);
                $im->setImageCompression(\Imagick::COMPRESSION_JPEG);
                $im->setImageCompressionQuality(10);
                $im->writeImage($newFileName);
                echo "converted \n";
             } else {
                 echo "exists \n";
            }
        }

        $im->destroy();
        return static::EXIT_CODE_NORMAL;

    }


}
