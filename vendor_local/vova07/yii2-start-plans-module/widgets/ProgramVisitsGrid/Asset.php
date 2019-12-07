<?php
namespace vova07\plans\widgets\ProgramVisitsGrid;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 6:32 PM
 */

class Asset extends AssetBundle
{
    public $sourcePath = '@vova07/plans/widgets/ProgramVisitsGrid/assets';
    public $js = [
        'js/program_visits_grid.js'
    ];
    public $depends = [
        JqueryAsset::class
    ];


}