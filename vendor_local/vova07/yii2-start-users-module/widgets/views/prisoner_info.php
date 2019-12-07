<?php
/**
 * @var $prisoner \vova07\users\models\Prisoner
 */
?>

<div class="box box-widget widget-user">
    <!-- Add the bg color to the header using any of the bg-* classes -->
    <div class="widget-user-header bg-aqua-active">
        <h3 class="widget-user-company"><?=$prisoner->prison->company->title?>(<?=$prisoner->prison->company->address?>)</h3>
        <h5 class="widget-user-fio">№<?=$prisoner->primaryKey?> <?=$prisoner->person->fio?>  <?=$prisoner->person->birth_year?> </h5>

    </div>
    <div class="widget-user-image">
        <img class="img-circle" src="<?=$prisoner->person->photo_preview_url?>" alt="User Avatar">
    </div>

</div>
