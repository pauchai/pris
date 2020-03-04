
<div class="box-comment">
    <!-- User image -->
    <?php if ($model->ownableitem->createdBy->person):?>
    <img class="img-circle img-sm" src="<?=$model->ownableitem->createdBy->person->photo_preview_url?>" alt="User Image">
    <?php endIf;?>


    <div class="comment-text">
                      <span class="username">
                          <?php if ($model->ownableitem->createdBy->person):?>
                            <?php echo $model->ownableitem->createdBy->person->fio?>
                          <?php endif ?>
                          <span class="text-muted pull-right">
                              <?=Yii::$app->formatter->asDate($model->ownableitem->item->created_at)?>
                          </span>
                      </span><!-- /.username -->
        <?=$model->content?>
    </div>
    <!-- /.comment-text -->
</div>


