
<?php if($query->count()):?>
<h4><?=\vova07\reports\Module::t('default', 'CONCEPTS_LABEL')?></h4>
<ol>
<?php foreach($query->all() as $concept):?>

    <li><?=$concept->title?> ,  <?=Yii::$app->formatter->asDate($concept->date_start)?></li>
<?php endforeach;?>

</ol>
<?php endif;?>
