<?php
    use yii\widgets\ContentDecorator;
?>

<?php ContentDecorator::begin([
        'viewFile' => '@vova07/humanitarians/views/backend/layouts/print_blank.php',
        'view' => $this,

    ]
);?>
<h3 align="center">

    BORDEROU DE ELIBERARE A MATERIALELOR
    PENTRU CECESITĂȚILE PENITENCIARULUI Nr.1 - TARACLIA
    pe luna septembrie 2019

</h3>
<?php echo $this->render('view.php',['model'=>$model,'dataProvider' => $dataProvider ,'searchModel'=>$searchModel])?>

<?php ContentDecorator::end();?>


