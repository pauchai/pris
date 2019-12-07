<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/29/19
 * Time: 12:48 PM
 */

namespace vova07\themes\adminlte2\widgets;


use yii\bootstrap\Html;
use yii\bootstrap\InputWidget;
use yii\bootstrap\Widget;
use yii\jui\DatePicker;

class InfoBox extends Widget
{

    public $title ='Likes';
    public $infoContent = '44.55';
    public $icon = 'files-o';
    public function run()
    {
        return $this->renderWidget();
    }
    public function renderWidget()
    {

        return Html::tag('div',
            Html::tag('span',
                Html::tag("i",'',['class' => 'fa fa-'. $this->icon]),
                    ['class'=>'info-box-icon bg-red']
                ).
            Html::tag('div',
                    Html::tag('span', $this->title,['class'=>'info-box-text']).
                    Html::tag('span', $this->infoContent,['class'=>'info-box-number']),
                    ['class' => 'info-box-content']
                ),
            ['class'=>'info-box']
        );




    }

}

?>
