<?php
/**
 * Created by PhpStorm.
 * User: pauk
 * Date: 8/17/19
 * Time: 2:49 PM
 * @var $this \yii\web\View
 * @var $dataProvider \yii\data\ActiveDataProvider
 */

$this->title = \vova07\plans\Module::t("default","PROGRAM");
$this->params['subtitle'] = 'LIST';
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        //      'url' => ['index'],
    ],
    // $this->params['subtitle']
];
?>

<?php $box = \vova07\themes\adminlte2\widgets\Box::begin(
    [
        'title' => $this->params['subtitle'],
        'buttonsTemplate' => '{create}'
    ]

);?>



<table id="program_visits_grid">
    <thead>
    <tr >
        <th>Prisoner</th>
        <th class="grid_head_container">Date<div class="grid_head_container_controls"><a class="pull-left" data="add_left"><i class="fa fa-chevron-circle-left"></i></a><a class="pull-right" data="add_right"><i class="fa fa-chevron-circle-right"></i></a></div></th>

    </tr>
    </thead>
<tr class="template" style="display:none">

<td>
    <?php echo \yii\bootstrap\Html::dropDownList('prisoner', [], \vova07\users\models\Prisoner::getListForCombo(),['prompt'=>\vova07\plans\Module::t('programs','SELECT')])?>
</td>
    <td >
        <?php echo \yii\bootstrap\Html::dropDownList('mark', [], \vova07\plans\models\ProgramVisit::getStatusesForCombo(),['prompt'=>\vova07\plans\Module::t('programs','MARK_IT')])?>
    </td>

</tr>
</table>

<?php \vova07\themes\adminlte2\widgets\Box::end()?>

<?php $this->registerCss(
<<<CSS
.grid_head_container {
  position:relative;
  width:100%;
  display:inline-block;
  
  }
.grid_head_container > .grid_head_container_controls {
  position:absolute;
  bottom:0;
  width:100%;
  background:rgba(255,255,255,0.7);

  box-sizing:content-box;
  display:none;
  z-index:10000;
  }
CSS

);
//\vova07\plans\widgets\ProgramVisitsGrid\Asset::register($this);
$this->registerJs(
<<< JS


headRow = $("#program_visits_grid > thead >tr");
      headRow.on("mouseover mouseout", '.grid_head_container', function(e) {
  $(this).find('.grid_head_container_controls').css("display", e.type === "mouseout"  ? "none" : "block");
  //alert (e.type);
});

var newRow = $('#program_visits_grid .template').clone().removeClass('template');

newRow = ProgramVisits.templateRow(newRow)
.appendTo('#program_visits_grid')
.fadeIn();



headRow.on('click','a[data=add_left]',function(){
   $(this).closest('th').clone().appendTo(headRow)
   console.log(  'add_left' + $(this).closest('th').index());
})
headRow.on('click','a[data=add_right]',function(){
    $(this).closest('th').clone().appendTo(headRow)
    console.log(  'add_right' + $(this).closest('th').index());
})
    
JS
);

$this->registerJs(
<<<JS
 ProgramVisits  = {
    
    init:function(){
        this.headRow
    },
    templateRow: function(row){
        return row;
    }

};
JS
,\yii\web\View::POS_END);



?>
