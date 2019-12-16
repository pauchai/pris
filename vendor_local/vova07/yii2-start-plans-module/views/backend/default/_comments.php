
<?php $box = \vova07\themes\adminlte2\widgets\Box::begin([
    'title' => 'comments',
])?>
<?php $box->beginFooter(['class'=>'box-comments'])?>
<?php $commentDemoUser = Yii::$app->user->identity?>
<?php if ($commentDemoUser->person):?>
    <div class="box-comment">
        <!-- User image -->
        <img class="img-circle img-sm" src="<?=$commentDemoUser->person->photo_preview_url?>" alt="User Image">

        <div class="comment-text">
                      <span class="username">
                        <?php echo $commentDemoUser->person->fio?>
                          <span class="text-muted pull-right">8:03 PM Today</span>
                      </span><!-- /.username -->
            Простить
        </div>
        <!-- /.comment-text -->
    </div>
    <!-- /.box-comment -->
    <div class="box-comment">
        <!-- User image -->
        <img class="img-circle img-sm" src="<?=$commentDemoUser->person->photo_preview_url?>" alt="User Image">

        <div class="comment-text">
                      <span class="username">
                        <?php echo $commentDemoUser->person->fio?>
                          <span class="text-muted pull-right">8:03 PM Today</span>
                      </span><!-- /.username -->
            Понять
        </div>
        <!-- /.comment-text -->
    </div>
    <div class="box-comment">
        <!-- User image -->
        <img class="img-circle img-sm" src="<?=$commentDemoUser->person->photo_preview_url?>" alt="User Image">

        <div class="comment-text">
                      <span class="username">
                        <?php echo $commentDemoUser->person->fio?>
                          <span class="text-muted pull-right">8:03 PM Today</span>
                      </span><!-- /.username -->
            Принять
        </div>
        <!-- /.comment-text -->
    </div>
    <!-- /.box-comment -->
<?php endif;?>
<?php $box->endFooter()?>

<?php \vova07\themes\adminlte2\widgets\Box::end()?>