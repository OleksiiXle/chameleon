<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Books;

Yii::$app->cache->flush();


$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="container" style="">
    <div class="row">
        <div class="col-md-6" style=" height: 620px; ">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'name',
                    [
                        'label'=>'Автор',
                        'value' => Books::getFullAuthorName($model->author_id),
                    ],
                    'date_create',
                    'date_update',
                    'date',
                ],
            ]) ?>
            <p>
                <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Подтвердите удаление книги?',
                        'method' => 'post',
                    ],
                ]) ?>
                <?= Html::a('O.K.', Books::getUpdateHome(), ['class' => 'btn btn-primary']) ?>

            </p>
        </div>
        <div class="col-md-6" style=" height: 620px; ">
            <img src="<?= $model->pathToPreview . 'i' . $model->preview;?>" height="500px" width="auto">
        </div>
        </div>
    <br>
</div>











