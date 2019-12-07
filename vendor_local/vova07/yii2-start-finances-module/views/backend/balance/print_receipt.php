<?php
/**
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$pageHeight = \vova07\base\helpers\Printer::FORMAT_A6_WIDTH_INCH;
$pageWidth = \vova07\base\helpers\Printer::FORMAT_A6_HEIGHT_INCH;

$this->registerCss(<<<CSS
    @page {
        size: ${pageWidth}in ${pageHeight}in;
        margin: 6mm
    }
CSS
);
?>
<?php  echo \yii\widgets\ListView::widget([
    'dataProvider' => $dataProvider,
    'itemView' => '_receipt',
    'summary' => ''
    
])?>

