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
use vova07\plans\models\Program;
?>


<!-- /.row -->
<!-- Main row -->
<div class="row">
    <?php if (Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONERS_LIST)):?>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <?php echo InfoBox::widget(
            [       'title' => \vova07\prisons\Module::t('default','PRISONERS_TITLE'),
                'infoContent' =>  Html::a(
                   Prisoner::find()->active()->count(),
                    ['/users/prisoner']
                ) ,
                'icon' => 'users'
            ]
        );
        ?>

    </div>
    <?php endif;?>

    <?php if (Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_DOCUMENTS_LIST)):?>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <?php
            $documentSearchModel = new \vova07\documents\models\backend\DocumentSearch;
            $infoContent = '';
            $infoContent =  Html::a(
                Document::find()->activePrisoners()->count(),
                ['/documents/default/index']
            );
            $infoContent .= Html::a(
                Html::tag('span',
                    Document::find()->activePrisoners()->expired()->count(),
                    ['class' => 'badge bg-red']
                ) . 'expired',
                ['/documents/default/index',$documentSearchModel->formName() => ['metaStatusId' => Document::META_STATUS_EXPIRATED]],
                ['class' =>'btn']
            );
             $infoContent .= Html::a(
            Html::tag('span',
                Document::find()->aboutExpiration()->activePrisoners()->count(),
                ['class' => 'badge bg-yellow']
            ) . 'about',
            ['/documents/default/index',$documentSearchModel->formName() => ['metaStatusId' => Document::META_STATUS_ABOUT_EXPIRATION]],
            ['class' =>'btn']
             )
        ?>
        <?php if ($infoContent):?>
        <?php echo InfoBox::widget(
            [       'title' => \vova07\prisons\Module::t('documents','DOCUMENTS_TITLE'),
                'infoContent' => $infoContent ,

                'icon' => 'file-alt'
            ]
        );?>
        <?php endif;?>
    </div>
    <?php endif;?>


    <div class="col-md-3 col-sm-6 col-xs-12">

        <?php
            $infoContent = '';

            if (Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PROGRAM_LIST))
                $infoContent .=  Html::a(
                    Html::tag('span',
                        Program::find()->active()->count() . ' / ' . ProgramPrisoner::find()
                            ->active()
                            ->forPrisonersActiveAndEtapped()
                            ->count(),
                        ['class' => 'badge bg-yellow']
                    ) . 'active',
                    ['/plans/programs/index'],
                    ['class' =>'btn']
                );
        ?>
        <?php if ($infoContent):?>
        <?php echo InfoBox::widget(
            [
                'title' => \vova07\plans\Module::t('default','PROGRAMS_PLANNED'),
                'infoContent' =>  $infoContent,
                'icon' => 'calendar-check'
            ]
        );?>
        <?php endif;?>
    </div>



</div>
<div class="row">
    <?php   if (Yii::$app->user->can(\vova07\rbac\Module::PERMISSION_PRISONERS_SECURITY_LIST)):    ?>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <?php echo InfoBox::widget(
            [       'title' => \vova07\plans\Module::t('default','PRISONER_SECURITY'),
                'infoContent' => Html::a(
                        Html::tag('span',
                            PrisonerSecurity::find()->andWhere(['type_id' => PrisonerSecurity::TYPE_246_g])->prisonerActive()->count(),
                            ['class' => 'badge bg-yellow']
                        ) . PrisonerSecurity::getTypesForCombo()[PrisonerSecurity::TYPE_246_g],
                        ['/prisons/prisoner-security/index'],
                        ['class' =>'btn']

                    ) .
                    Html::a(
                        Html::tag('span',
                            PrisonerSecurity::find()->andWhere(['or', 'type_id=' . PrisonerSecurity::TYPE_251 ,'type_id=' . PrisonerSecurity::TYPE_250 ])->prisonerActive()->count(),
                            ['class' => 'badge bg-yellow']
                        ) . PrisonerSecurity::getTypesForCombo()[PrisonerSecurity::TYPE_251] . ' ' . PrisonerSecurity::getTypesForCombo()[PrisonerSecurity::TYPE_250],
                        ['/prisons/prisoner-security/index'],
                        ['class' =>'btn']
                    ),


                'icon' => 'hourglass'
            ]
        );?>
    </div>
    <?php endif;?>
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