
<?php if($query->count()):?>
<h4><?=\vova07\reports\Module::t('default', 'PROGRAMS_LABEL')?></h4>
<ol>
<?php foreach($query->all() as $prisonerProgram):?>

    <li><?=$prisonerProgram->programDict->title?>, <?=$prisonerProgram->date_plan?> </li>
<?php endforeach;?>

</ol>
<?php endif;?>