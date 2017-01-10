<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
            <br><br><input id = "newPreviewTxt" name = "newPreviewTxt" value ="" size="70" disabled>
        </div>
        <?php ActiveForm::end(); ?>

        <div class="col-md-6" style=" height: 620px; padding-top: 20px">
            <?php $form = ActiveForm::begin(['options' => [
                'enctype' => 'multipart/form-data',
                'name'  => 'getPreview',
                'id' => 'getPreview',
            ]]); ?>
                    <? if (!($model->preview == '')):?>
                         <img src="<?= $model->pathToPreview . $model->preview;?>" id="previewIMG" height="400" width="auto" ">
                  <?else:?>
                        <img src="<?= $model->pathToPreview . $model->tmpImage;?>" id="previewIMG" height="400" width="auto"  >
                  <?endif;?>
                  <?= $form->field($model, 'imageFile')->fileInput(['onchange' =>'upload.click();']) ?>
                  <button type="submit" id="upload" hidden></button>
            <?php ActiveForm::end(); ?>

        </div>
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
      url: "../lib/myLib.php",
      type: $that.attr("method"),
      contentType: false,
      processData: false,
      data: formData,
      success: function(response){
             //   alert("OK " + response);
                document.getElementById("newPreviewTxt").value =  response;
                document.getElementById("previewIMG").src =  response;
      //  $("#newPreviewTxt").html(response);
      }
    });
  });
});
         '
);
?>