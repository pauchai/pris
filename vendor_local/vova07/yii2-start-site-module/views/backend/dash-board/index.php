<?php
/* @var \yii\web\View $this View
 * 
 * 
 *
 *
*/
use yii\bootstrap\Html;
use vova07\themes\adminlte2\widgets\InfoBox;
use vova07\users\models\Prisoner;
use vova07\documents\models\Document;
use vova07\plans\models\ProgramPrisoner;
use vova07\prisons\models\PrisonerSecurity;
?>


<!-- /.row -->
<!-- Main row -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <?php echo InfoBox::widget(
            [       'title' => \vova07\prisons\Module::t('default','PRISONERS_TITLE'),
                'infoContent' =>  Html::a(
                   Prisoner::find()->count(),
                    ['/users/prisoner']
                ) ,
                'icon' => 'users'
            ]
        );
        ?>

    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <?php echo InfoBox::widget(
            [       'title' => \vova07\prisons\Module::t('documents','DOCUMENTS_TITLE'),
                'infoContent' => Html::a(
                    Document::find()->count(),
                    ['/prisons/documents']
                ) ,

                'icon' => 'file-alt'
            ]
        );?>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <?php echo InfoBox::widget(
            [       'title' => \vova07\plans\Module::t('default','PROGRAMS_PLANNED'),
                'infoContent' => Html::a(
                    Html::tag('span',
                         ProgramPrisoner::find()->planned()->count(),
                        ['class' => 'badge bg-yellow']
                    ) . 'planned',
                    ['/plans/program-plans'],
                        ['class' =>'btn']

                    ) .
                    Html::a(
                        Html::tag('span',
                            ProgramPrisoner::find()->active()->count(),
                            ['class' => 'badge bg-yellow']
                        ) . 'active',
                        ['/plans/programs/index'],
                        ['class' =>'btn']
                    ),

                'icon' => 'calendar-check'
            ]
        );?>
    </div>

    <div class="col-md-3 col-sm-6 col-xs-12">
        <?php echo InfoBox::widget(
            [       'title' => \vova07\plans\Module::t('default','PRISONER_SECURITY'),
                'infoContent' => Html::a(
                        Html::tag('span',
                            PrisonerSecurity::find()->andWhere(['type_id' => PrisonerSecurity::TYPE_246_g])->count(),
                            ['class' => 'badge bg-yellow']
                        ) . PrisonerSecurity::getTypesForCombo()[PrisonerSecurity::TYPE_246_g],
                        ['/prisons/prisoner-security/index'],
                        ['class' =>'btn']

                    ) .
                    Html::a(
                        Html::tag('span',
                            PrisonerSecurity::find()->andWhere(['or', 'type_id=' . PrisonerSecurity::TYPE_251 ,'type_id=' . PrisonerSecurity::TYPE_250 ])->count(),
                            ['class' => 'badge bg-yellow']
                        ) . PrisonerSecurity::getTypesForCombo()[PrisonerSecurity::TYPE_251] . ' ' . PrisonerSecurity::getTypesForCombo()[PrisonerSecurity::TYPE_250],
                        ['/prisons/prisoner-security/index'],
                        ['class' =>'btn']
                    ),


                'icon' => 'hourglass'
            ]
        );?>
    </div>

</div>
<div class="row">
    <table >
        <tr>
            <td></td>
            <td> <?= Yii::$app->user->can("") ?></td>
        </tr>
    </table>
</div>
<div class="row">

    <!-- Left col -->
    <section class="col-lg-7 connectedSortable">

    </section>
    <!-- /.Left col -->
    <!-- right col (We are only adding the ID to make the widgets sortable)-->
    <section class="col-lg-5 connectedSortable">


    </section>
    <!-- right col -->
</div>
<!-- /.row (main row) -->