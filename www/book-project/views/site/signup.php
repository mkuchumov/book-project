<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Регистрация';
?>
    <h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'username') ?>
<?= $form->field($model, 'email') ?>
<?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Зарегистрироваться', ['class' => 'btn btn-success']) ?>
    </div>

<?= Html::a('Уже есть аккаунт? Войти', ['site/login']) ?>

<?php ActiveForm::end(); ?>
