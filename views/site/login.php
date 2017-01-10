<?use yii\widgets\ActiveForm;?>



<?php $form = ActiveForm::begin(); ?>
<?= $form->field($login_model, 'userName')->textInput(['autofocus' => true]) ?>
<?= $form->field($login_model, 'userPassword')->passwordInput() ?>
    <div>
        <button type = 'submit' class = 'btn btn-primary'>Войти</button>

    </div>


<?php ActiveForm::end(); ?>