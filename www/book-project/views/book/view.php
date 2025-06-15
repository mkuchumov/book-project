<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Book $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="book-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'year',
            'description:ntext',
            'isbn',
        ],
    ]) ?>


    <?php
        if ($model->cover_image): ?>
        <img src="<?= Yii::$app->request->baseUrl . '/' . $model->cover_image ?>" alt="Обложка" style="max-width: 200px;">
    <?php else: ?>
        <p>Нет обложки</p>
    <?php endif; ?>


    <p>
        <?= "<h3>Авторы:</h3>" ?>
        <?php
        foreach ($model->authors as $author) {
            echo Html::a($author->name, ['author/view', 'id' => $author->id]) . "<br>";
        }
        ?>
    </p>

</div>
