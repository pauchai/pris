<?php
namespace vova07\prisons\widgets;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $template = "{view} {update} {departments} {delete}";

    protected function initDefaultButtons()
    {
        $this->initDefaultButton('view', 'eye-open');
        $this->initDefaultButton('update', 'pencil');
        $this->initDefaultButton('departments', 'server');
        $this->initDefaultButton('delete', 'trash', [
            'data-confirm' => \Yii::t('yii', 'Are you sure you want to delete this item?'),
            'data-method' => 'post',
        ]);
    }

}