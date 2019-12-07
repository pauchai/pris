<?php
namespace vova07\humanitarians\components;
use http\Url;
use vova07\humanitarians\Module;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 9/24/19
 * Time: 10:05 AM
 */

class HumanitarianColumn extends \uran1980\yii\modules\i18n\components\grid\ActionColumn
{
    public $humanitarianItem;
    public $humanitarianIssue;
    public $action;
    public $massAction = ['mass-add'];

    public function createUrl($action, $model, $key, $index)
    {
       $key = [
           'prisoner_id' => $key,
           'item_id' => $this->humanitarianItem->primaryKey,
           'issue_id' => $this->humanitarianIssue->primaryKey,
           ];

        $params = is_array($key) ? $key : ['id' => (string) $key];
        $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

        return \yii\helpers\Url::toRoute($params);
    }
    public function renderHeaderCell()
    {
        return Html::tag('th',
            $this->renderHeaderCellContent() .
            Html::tag('div',
                '<span class="btn-ajax-wrap">' . Html::a('<i class="glyphicon glyphicon-plus' .  '"></i>', \yii\helpers\Url::to($this->massAction), [
                    'class'                 => 'btn btn-xs ' .  'btn-info' .'  btn-ajax',
                    'action'                => 'toggle-item',
                    'data'                  => [
                        //'toggle'            => 'confirmation',
                        'popout'            => true,
                        'singleton'         => true,
                        'placement'         => 'left',
                        'title'             => Module::t('default','Are you sure you want to restore this item?'),
                        'method'            => 'post',
                        'btn-ok-label'      => Module::t('default','Yes'),
                        'btn-ok-class'      => 'btn-xs btn-success',
                        'btn-cancel'        => Module::t('default','No'),
                        'btn-cancel-class'  => 'btn-xs btn-warning',
                    ],
                    'before-send-title'     => Module::t('default','Request sent'),
                    //   'before-send-message'   => Module::t('default','Please, wait...'),
                    'success-title'         => Module::t('default','Server Response'),
                    'success-message'       => Module::t('default','Message successfully deleted.'),
                ]) . '</span>'
            ),
            $this->headerOptions);
    }
    protected function initDefaultButtons()
    {
        parent::initDefaultButtons();
        if (!isset($this->buttons['toggle-item'])){
            $humanitarianItem = $this->humanitarianItem;
            $issueModel = $this->humanitarianIssue;
            $this->buttons['toggle-item'] = function($url, $model, $key) use ($humanitarianItem, $issueModel) {
                $item = \vova07\humanitarians\models\HumanitarianPrisoner::findOne(
                    [
                        'prisoner_id' => $key,
                        'issue_id' => $issueModel->primaryKey,
                        'item_id' => $humanitarianItem->primaryKey
                    ]
                );
                if ($item) {
                    $cssButtonClassName = "btn-success";
                    $cssIconClassName = "glyphicon-minus";
                } else {
                    $cssButtonClassName = "btn-default";
                    $cssIconClassName = "glyphicon-plus";
                }
                return '<span class="btn-ajax-wrap">' . Html::a('<i class="glyphicon ' . $cssIconClassName . '"></i>', $url, [
                        'class'                 => 'btn btn-xs ' . $cssButtonClassName. '  btn-ajax',
                        'action'                => 'toggle-item',
                        'data'                  => [
                            //'toggle'            => 'confirmation',
                            'popout'            => true,
                            'singleton'         => true,
                            'placement'         => 'left',
                            'title'             => Module::t('default','Are you sure you want to restore this item?'),
                            'method'            => 'post',
                            'btn-ok-label'      => Module::t('default','Yes'),
                            'btn-ok-class'      => 'btn-xs btn-success',
                            'btn-cancel'        => Module::t('default','No'),
                            'btn-cancel-class'  => 'btn-xs btn-warning',
                        ],
                        'before-send-title'     => Module::t('default','Request sent'),
                        //   'before-send-message'   => Module::t('default','Please, wait...'),
                        'success-title'         => Module::t('default','Server Response'),
                        'success-message'       => Module::t('default','Message successfully deleted.'),
                    ]) . '</span>';
            };
        }




    }

}