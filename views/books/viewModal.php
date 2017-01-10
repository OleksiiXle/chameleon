<?php
use yii\widgets\DetailView;
?>

<div class="container" style="">
    <div class="row">

        <div class="col-md-3" style=" height: 600px; ">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    [
                        'label'=>'Автор',
                        'value' => app\models\Books::getFullAuthorName($model->author_id),
                    ],
                    'date_create',
                    'date_update',
                    'date',
                ],
            ]) ?>
         </div>
         <div class="col-md-4" style=" height: 600px; ">
             <img src="<?= $model->pathToPreview . 'i' . $model->preview;?>"  width="350px" height="auto">

         </div>
    </div>


</div>




