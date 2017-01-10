<?use yii\widgets\ActiveForm;?>



<?php $form = ActiveForm::begin(); ?>
        <?= $form->field($model, 'userName')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'userPassword')->passwordInput() ?>
<div>
        <button type = 'submit' class = 'btn btn-primary'>Submitik</button>

</div>


<?php ActiveForm::end(); ?>