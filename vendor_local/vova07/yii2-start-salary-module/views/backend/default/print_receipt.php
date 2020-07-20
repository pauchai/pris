<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$pageHeight = \vova07\base\helpers\Printer::FORMAT_A4_HEIGHT_INCH / 3   ;
//$pageHeight = $pageHeight - $pageHeight/30;
$pageWidth = \vova07\base\helpers\Printer::FORMAT_A4_WIDTH_INCH / 3   ;
//$pageWidth = $pageWidth - $pageWidth/30;

$this->registerCss(<<<CSS
        @page {
        size: ${pageWidth}in ${pageHeight}in ;
        margin: 3mm;
        }
    body{
    font-size:9px !important;
    }
    table ,table td{
    font-size:10px !important;
        border-width:1px;
        border-style: solid;
    }
CSS
);
?>


<?php  echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_receipt',
    'summary' => ''
    
])?>

