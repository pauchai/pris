<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 4/26/21
 * Time: 9:17 AM
 */

namespace vova07\base\helpers;

use vova07\documents\models\Document;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;



class HtmlExtra
{
    public static function getLabelStatusAndDocument($statusTitle, $statusHref, Document $docModel=null, $docRoute=['/documents/default/view' ,'id' => null])
    {
        $docContent = '';
        if ($docModel){
            $docRoute['id'] = $docModel->primaryKey;
            $docIcon = Html::tag('i','',['class' => 'fa fa-file']);
            if ($docModel->isExpired()){
                $docContent = Html::a( $docIcon . ' ' . 'expired' . ' ' . \Yii::$app->formatter->asRelativeTime($docModel->date_expiration ),
                    $docRoute,
                    ['title' => $docModel->type, 'class'=>' label label-danger']);

            } else {
                if ($docModel->isAboutExpiration()){
                    $docContent = Html::a($docIcon . ' ' . 'expiring' . ' ' . \Yii::$app->formatter->asRelativeTime($docModel->date_expiration ),
                        $docRoute,
                        ['title' => $docModel->type, 'class'=>'label label-warning']);

                }


            }
        }
        $res = Html::a($statusTitle, $statusHref,  ['class' => 'label label-info']) . $docContent ;
        return $res;
    }

}