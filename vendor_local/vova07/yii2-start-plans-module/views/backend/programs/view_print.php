<?php
/**
 * @var $this \yii\web\View
 */

use yii\bootstrap\Html;
use vova07\plans\Module;
use kartik\grid\GridView;

$pageWidth = \vova07\base\helpers\Printer::FORMAT_A4_WIDTH_INCH;
$pageHeight = \vova07\base\helpers\Printer::FORMAT_A4_HEIGHT_INCH;

$this->registerCss(<<<CSS
        @page {
        size: ${pageHeight}in ${pageWidth}in;
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
    body, table {
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
    .anexa {
        font-size: 100%
    }
    
CSS
)
?>

<table>
    <tr>
        <td width="60%"></td>
        <td>
            <p style="margin:0px;padding:0px" class="anexa" align="right">Anexa nr.3</p>
            <p class="anexa" align="right">
                la Metodologia privind procedura de elaborare şi implimentare a Planului individual de resocializare pentru condamnatul adult, aprobată prin ordinul DIP nr.34 din 31.01.2018
            </p>
        </td>
    </tr>
</table>
<br/>
<br/>
<br/>

<h3  style="text-align:center;font-weight: bolder;" >Fişa de prezenţă</h3>
<h4  style="text-align:center;text-decoration: underline;" ><i>la Programul de <b>"<?=$model->programDict->title?>"</b></i></h4>

<?php $gridColumns = [
    ['class' => yii\grid\SerialColumn::class],
    [
        'attribute' => 'prisoner.person.fio',
        'value' => function($model){return $model->prisoner->getFullTitle(true);}
    ],
]

?>
<?php
$datesCount = 0;
foreach($model->getProgramVisits()->distinctDates() as $dateValue){
    $datesCount++;
    $date = DateTime::createFromFormat('Y-m-d', $dateValue);
    $gridColumns[] = [
        'header' => Html::tag('div', $date->format('d-m') . "<br/>". $date->format('Y'),['style' => 'text-align:center']),
        'content' => function($model)use($dateValue){
            $programVisit = \vova07\plans\models\ProgramVisit::findOne([
                'program_prisoner_id'=>$model->primaryKey,
                'date_visit' => $dateValue
            ]);
            if ($programVisit){
                return Html::tag('div', $programVisit->getStatus(), ['style' => 'text-align:center']);
            }
        }

    ];
}
?>



<?php echo GridView::widget(['id' => 'participants','dataProvider' => $dataProvider,
    'columns' => $gridColumns,
    'beforeHeader' =>[
        [
                'columns' => [
                    [],
                    [],
                    [
                        'content' => Html::tag('div', 'Data desfăşurării şedinţelor programului.',
                            [
                                    'style' => 'text-align:center; font-weight:bolder'
                            ]),
                        'options' => [
                                'colspan' => $datesCount
                        ]
                    ]
                ]

        ]
    ],

])?>

<p><b>Notă:</b></p>
<p><b>p</b> - prezent,</p>
<p><b>n</b> - absenţa nemotivată,</p>
<p><b>m</b> - absenţa motivată,</p>


<p style="text-align:right;">______________________________________________________________________________________________</p>
<p style="text-align:right;">______________________________________________________________________________________________</p>