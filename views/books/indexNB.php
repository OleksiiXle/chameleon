<?php
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\Books;
use yii\widgets\ActiveForm;


$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;

//--- записать в сессию точку возврата
Books::setUpdateHome();
?>
<?php Pjax::begin(); ?>
<div class="container" >
    <div class="row">
        <div class="col-md-2" style=" height: 150px; ">
            <p>
                <?= Html::a('Новая книга', ['create'], ['class' => 'btn btn-success']) ?>
            </p>
        </div>
        <!-- форма изменения, добавления и удаления фильтра-->
        <div class="col-md-10 " style=" height: 150px; background-color: #c1e2b3">
            <?php $form = ActiveForm::begin(['options' => ['name'  => 'BooksFind', 'placeholdersFromLabels' => true]]); ?>
            <div class="col-md-5 ">
                <?= $form->field($queryModel, 'name')->textInput(['placeholder' => 'название книги']) ?>
                <?= $form->field($queryModel, 'author_id')->dropDownList($queryModel->authorsListSLV,['placeholder' => 'автор']) ?>
            </div>
            <div class="col-md-5 ">
                <?= $form->field($queryModel, 'd1')->textInput(['type' => 'date', 'placeholder' => 'дата выхода книги с:' ]) ?>
                <?= $form->field($queryModel, 'd2')->textInput(['type' => 'date', 'placeholder' => 'до:']) ?>
                <input id="mode" name="mode" value="" hidden>
            </div>
            <div class="col-md-2 ">
                <br>
                <?= Html::submitButton('Примерить фильтр' , ['class' => 'btn btn-primary', 'onclick' => 'document.getElementById("mode").value = "filterOn";']) ?>
            </div>
            <div class="col-md-2 ">
                <br>
                <?= Html::submitButton('Сбросить фильтр' , ['class' => 'btn btn-danger', 'onclick' => 'document.getElementById("mode").value = "filterOff";']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<!-- вывод данных книг-->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'label' => 'Превью',
                'format' => 'raw',
                'content' => function($data){
                    return Html::img($data->pathToPreview . 'i' . $data->preview,[
                        'alt'=>'yii2 - картинка в gridview',
                        'height' => '75',
                        'onclick' => 'Zoom(this);' //--- по клику - изменение размера
                    ]);
                },
            ],
            [
                'attribute'=>'author_id',
                'label'=>'Автор',
                'format'=>'text',
                'content'=>function($data){
                    return \app\models\Books::getFullAuthorName($data->author_id);
                },
                'filter' => \app\models\Books::getAuthorList()
            ],
            [
                'attribute'=>'date',
                'label'=>'Дата выхода книги',
                'content'=>function($data){
                    return \app\models\Books::formatDateHuman($data->date, false, false, 1);
                },
            ],
            [
                'attribute'=>'date_create',
                'label'=>'Дата добавления',
                'content'=>function($data){
                    return \app\models\Books::formatDateHuman($data->date_create, true, false, 1);
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'buttons'=>[
                    'view'=>function($url,$model){    //--- просмотр в модальном окне
                            $url=Yii::$app->getUrlManager()->createAbsoluteUrl(['/books/view','id'=>$model->id]);
                             return \yii\helpers\Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url,
                                 [
                                    'title' => 'Посмотреть',
                                     'data-toggle'=>'modal',
                                     'data-backdrop'=>false,
                                     'data-target' => '#mymodal',
                                     'data-remote'=>$url
                     ]);
                    },
                    'update'=>function($url,$model){  //---- редактирование
                        $url=Yii::$app->getUrlManager()->createAbsoluteUrl(['/books/update','id'=>$model->id]);
                        return \yii\helpers\Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url,
                            [
                                'title' => 'Редактировать',

                            ]);
                    },
                ],
                'template'=>'{view}{update}{delete}'],
        ],
    ]);
    ?>
    <?php Pjax::end(); ?>

<!-- модальное окно, в которое будет выводиться информация о книге для просмотра -->
    <?Modal::begin([
                'header' => '<b>Книга</b>',
                'id'=>'mymodal',
                 'size' => 'modal-lg',
                 ]);?>

    <?php Modal::end();?>
<!-- загрузка информации в модальное окно для просмотра -->
    <?php
    $this->registerJs(
        ' $(document).on("click","[data-remote]",function(e) 
                    {
                     e.preventDefault();
                     $("div#mymodal .modal-body").load($(this).data("remote"));
                    });
            $("#Assigs").on("hidden.bs.modal", function (e) 
            {
               $("div#mymodal .modal-body").html("");
            }); 
         '
    );
    ?>
<!-- изменение размеров превью при клике -->
    <script>
        function Zoom(im) {
            if(im.height == 200) im.height = 75;
           else im.height = 200;
         }
    </script>


