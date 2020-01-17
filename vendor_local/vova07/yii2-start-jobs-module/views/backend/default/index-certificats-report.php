<?php
/**
 * @var $this \yii\web\View
 */

$pageWidth = \vova07\base\helpers\Printer::FORMAT_A5_WIDTH_INCH;
$pageHeight = \vova07\base\helpers\Printer::FORMAT_A5_HEIGHT_INCH;

$this->registerCss(<<<CSS
        @page {
        size: ${pageWidth}in ${pageHeight}in;
        margin: 6mm
        }
    body{
    font-size:12px
    }
CSS
        );
?>


<?php echo \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
    'itemView' => '_certificat',
      'summary' => '',

])?>