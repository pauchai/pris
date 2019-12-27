<?php

namespace vova07\users;



/**
 * Module [[Users]]
 * Yii2 users module.
 */
class Module extends \vova07\base\components\Module
{

    public  $personPhotoPath = "@statics/web/persons/photos/";
    public  $personPreviewPath = "@statics/web/persons/previews/";
    public  $personPhotoUrl = "/statics/persons/photos/";
    public  $personPreviewUrl = "/statics/persons/previews/";
    public  $personPreviewWidth = 200;
    public  $personPhotoWidth = 300;
    public  $personPhotoAspectRatio = 3/4;


}
