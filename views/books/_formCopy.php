<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

//yii\caching\Cache::flush();
?>

<div class="container" style="background-color: azure; ">
    <div class="row">

        <?php $form = ActiveForm::begin(['options' => [ 'name'  => 'BooksEdit']]); ?>
        <div class="col-md-6" style=" height: 620px; ">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'author_id')->dropDownList($model->authorsListSLV) ?>
            <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Сохранить изменения', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <input id = "newPreviewTxt" name = "newPreviewTxt" value ="" hidden>
        </div>
        <?php ActiveForm::end(); ?>

        <?php Pjax::begin(); ?>
        <div class="col-md-6" style=" height: 620px; ">
            <form action="../lib/myLib.php" method="post" id="getPreview" name="getPreview" enctype="multipart/form-data">
                <? if (!($model->preview == '')):?>
                    <img src="<?= $model->pathToPreview . $model->preview;?>" id="previewIMG" height="400" width="auto" class="img-thumbnail">
                <?else:?>
                    <img src="<?= $model->pathToPreview . $model->tmpImage;?>" id="previewIMG" height="400" width="auto" class="img-thumbnail">
                <?endif;?>
                <input type="file" name="newPreview" id="newPreview""><br>
                <input type="submit" id="submit" value="Обновить превью" >
            </form>
        </div>
        <?php Pjax::end(); ?>
    </div>
</div>


<?php
//--------------------загрузка файла изображения -----------------------------------------------------------------------------------------
$this->registerJs(
    '
  $(function(){
  $("#getPreview").on("submit", function(e){
    e.preventDefault();
    var $that = $(this),
    formData = new FormData($that.get(0)); 
    $.ajax({
      url: $that.attr("action"),
      type: $that.attr("method"),
      contentType: false, 
      processData: false, 
      data: formData,
      success: function(response){
             //   alert("OK " + response);
                document.getElementById("newPreviewTxt").value =  response;
                document.getElementById("previewIMG").src =  response;
                document.getElementById("newPreview").value =  response;
      //  $("#newPreviewTxt").html(response);
      }
    });
  });
});
         '
);
?>

<?//**************************************************** отладка  ******************************************?>
<style>
    .tDebug{
        position: fixed;
        left: 75%;
        top: 5%;
        width: 20%;
        height: 85%;
        overflow: auto ;
        float: left;
    }
</style>

<?if (1 == 0):?>
    <div class="tDebug">
        <b>отладка</b>
        <?$session = Yii::$app->session;?>
        <pre>
             <?= var_dump($session['homeUpdate'])?>
        </pre>
    </div>
<?endif;?>

