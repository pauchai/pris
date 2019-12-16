<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>



        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="company">
                    <a href="">
                        <?=Yii::$app->base->company->title?>
                    </a>
                </li>
                <?php
                $languageItem = new cetver\LanguageSelector\items\DropDownLanguageItem([
                    'languages' => [
                        'en-EN' => '<span class="flag-icon flag-icon-us"></span> EN',
                        'ru-RU' => '<span class="flag-icon flag-icon-ru"></span> RU',
                        'ro-RO' => '<span class="flag-icon flag-icon-ro"></span> RO',
                    ],
                    'options' => ['encode' => false],
                ]);
                $languageItem = $languageItem->toArray();
               // $languageDropdownItems = \yii\helpers\ArrayHelper::remove($languageItem, 'items');
                echo \yii\bootstrap\Nav::widget([
                    'options' => ['class' => 'navbar-nav navbar-left'],
                    'items' => [
                         $languageItem
                    ]
                ]);
                ?>

                <!-- User Account: style can be found in dropdown.less -->

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <?php if (Yii::$app->user->identity->person):?>
                        <img src="<?=Yii::$app->user->identity->person->photo_preview_url?>" class="user-image" alt="User Image"/>
                        <span class="hidden-xs"><?=Yii::$app->user->identity->person->fio?></span>
                        <?php endif;?>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <?php if (Yii::$app->user->identity->person):?>
                        <li class="user-header">
                            <img src="<?=Yii::$app->user->identity->person->photo_preview_url?>" class="img-circle"
                                 alt="User Image"/>

                            <p>
                                <?=Yii::$app->user->identity->person->fio?>
                                <small><?=Yii::$app->user->identity->role?></small>
                                <small><?=Yii::$app->user->identity->person->officer->company->title?>-<?=Yii::$app->user->identity->person->officer->department->title?></small>


                            </p>
                        </li>
                        <?php endif;?>
                        <!-- Menu Body -->

                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="<?=\yii\helpers\Url::toRoute(['/users/default/view','id'=>Yii::$app->user->id])?>" class="btn btn-default btn-flat"><?=\vova07\site\Module::t('default','PROFILE')?></a>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    \vova07\site\Module::t('default','LOGOUT'),
                                    ['/site/default/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

                <!-- User Account: style can be found in dropdown.less -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</header>
