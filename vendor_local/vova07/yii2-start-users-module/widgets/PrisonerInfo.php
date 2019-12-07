<?php
namespace vova07\users\widgets;

use yii\bootstrap\Widget;

class PrisonerInfo extends Widget
{
    public $prisoner;
    public function run()
    {

        return $this->render('prisoner_info',['prisoner'=>$this->prisoner]);
    }

}