<?php
/**
 * @var $this \yii\web\View
 * @var $model \vova07\plans\models\ProgramVisit
 */
?>


<span class="label label-<?=$model->resolveStatusStyle()?>"><?php echo $model->getStatus();?></span>