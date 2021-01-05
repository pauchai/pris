
<?php if($query->count()):?>
<h4><?=\vova07\reports\Module::t('default', 'PROGRAMS_LABEL')?></h4>
<ol>
<?php foreach($query->all() as $prisonerProgram):?>

    <li><?=$prisonerProgram->programDict->title?>,  (<?=$prisonerProgram->date_plan?>) <?=Yii::$app->formatter->asDate($prisonerProgram->finished_at,'YYYY')?> <?=\yii\helpers\Html::tag('i', $prisonerProgram->getMarkTitle() , ['class' => 'label label-' .\vova07\plans\models\ProgramPrisoner::resolveMarkStyleById($prisonerProgram->mark_id)])?> <?=$prisonerProgram->status?> </li>
<?php endforeach;?>

</ol>
<?php endif;?>

