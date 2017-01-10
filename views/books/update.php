<?php
use yii\helpers\Html;

$this->title = 'Редактировать данные: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Books', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Редактировать';
?>
<div class="books-update">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', ['model' => $model]) ?>

</div>
