<?php
/**
 * @var $this \yii\web\View
 * @var $model \vova07\concepts\models\ConceptVisit
 */
?>


<span class="label label-<?=$model->resolveStatusStyle()?>"><?php echo $model->getStatus();?></span>