<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var $model app\models\SubscriptionForm */

$this->title = 'Подписаться на автора';
?>
<h1><?= Html::encode($this->title) ?></h1>

<div class="subscription-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phone')->textInput(['placeholder' => '+79123456789']) ?>
    <?= $form->field($model, 'author_id')->hiddenInput()->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Подписаться', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
