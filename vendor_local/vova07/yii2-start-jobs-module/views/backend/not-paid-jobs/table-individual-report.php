<?php
/**
 * @var $this \yii\web\View
 */

//$pageHeight = \vova07\base\helpers\Printer::FORMAT_A5_WIDTH_INCH;
//$pageWidth = \vova07\base\helpers\Printer::FORMAT_A5_HEIGHT_INCH;

// Set Page

  list($pageWidth, $pageHeight) = \vova07\base\helpers\Printer::getGeometry(\vova07\base\helpers\Printer::FORMAT_A5, true);  $this->registerCss(<<<CSS
        
        @page {
        /*size: ${pageWidth}in ${pageHeight}in;*/
        margin: 6mm
        }
    body{  font-size:20px !important}

CSS
        );
?>

<?php echo \yii\widgets\ListView::widget([
        'dataProvider' => $dataProvider,

    'itemView' => '_individual',
      'viewParams'=> [
          'dataProvider' => $dataProvider,
          'searchModel' => $searchModel,
          'director' => $director,
          'officer' => $officer,
      ],
      'summary' => '',

])?>