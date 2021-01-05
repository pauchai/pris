<?php
/**
 * @var $queryPaid \vova07\jobs\models\JobNormalizedViewDaysQuery
 * @var $queryNotPaid \vova07\jobs\models\JobNormalizedViewDaysQuery
 */

?>
<?php if($queryPaid->count() || $queryNotPaid->count()):?>
<h4><?=\vova07\reports\Module::t('default', 'JOBS_LABEL')?></h4>
<ol>
<li><?=\vova07\reports\Module::t('default', 'JOBS_PAID_LABEL'). ':'. $queryPaid->count() ?></li>
    <li><?=\vova07\reports\Module::t('default', 'JOBS_NOT_PAID_LABEL'). ':'.  $queryNotPaid->sum('hours') ?></li>

</ol>
<?php endif;?>

