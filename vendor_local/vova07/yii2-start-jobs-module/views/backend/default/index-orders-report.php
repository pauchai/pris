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
<?php
$this->registerCss(<<<CSS
    body {
        font-family: "Times New Roman";
        font-size:14px;
    }
    .field {
        display:inline-block;
        border:none;
        border-bottom-color: black;
        border-bottom-width: 1px;
        border-bottom-style: solid;
        text-align: center;
        font-weight: bolder;
    }
CSS
)
?>

<?php echo \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,
    'itemView' => '_order',
      'summary' => '',

])?>