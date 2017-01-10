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
        <div class="col-md-6" style=" height: 620px; padding-top: 10px ">
            <?php $form = ActiveForm::begin(['options' => [
                'enctype' => 'multipart/form-data',
                'name'  => 'PreviewEdit',
                'id' => 'qwert',
                'action' => '../lib/myLib.php'
            ]]); ?>
            <? if (!($model->preview == '')):?>
                <? if(strlen($newPreview) > 0):?>
                        <img src="<?= $model->pathToNewPreview . $newPreview;?>" id="previewIMG" height="400px" width="auto" >
                    <?else:?>
                        <img src="<?= $model->pathToPreview . $model->preview;?>" id="previewIMG" height="400px" width="auto" >
                <?endif;?>
            <?else:?>
                <? if(strlen($newPreview) > 0):?>
                         <img src="<?= $model->pathToNewPreview . $newPreview;?>" id="previewIMG" height="400px" width="auto" >
                    <?else:?>
                         <img src="<?= $model->pathToPreview . $model->tmpImage;?>" id="previewIMG" height="400px" width="auto" >
               <?endif;?>
            <?endif;?>
                     <?= $form->field($model, 'imageFile')->fileInput(['onchange' =>
                                            'document.getElementById("changePhoto").value = "Yes";
                                             document.PreviewEdit.submit();']) ?>
                     <input name = "changePhoto" id = "changePhoto" value = "" size="20" hidden >
            <?php ActiveForm::end(); ?>

        </div>
        <?php Pjax::end(); ?>
    </div>
</div>



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

<?if (1 == 1):?>
    <div class="tDebug">
        <b>отладка </b>
        <?='preview=>' .$model->pathToNewPreview . $newPreview;?>
        <pre>
             <?= var_dump($model)?>
        </pre>
    </div>
<?endif;?>

