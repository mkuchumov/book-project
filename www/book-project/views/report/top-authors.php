<?php

use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\BookSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'ТОП-10 авторов по количеству книг за год';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>

   <!-- Форма для выбора года -->
    <div class="row mb-4">
        <div class="col-md-4">
            <form method="get" action="<?= Yii::$app->urlManager->createUrl(['report/top-authors']) ?>">
                <div class="input-group mb-3">
                    <input type="number"
                           name="year"
                           class="form-control"
                           min="1900"
                           max="<?= date('Y') ?>"
                           value="<?= Html::encode($year) ?>"
                           placeholder="Год">
                    <button class="btn btn-primary" type="submit">Сформировать</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица результатов -->
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => 'Автор',
            ],
            [
                'attribute' => 'books_count',
                'label' => 'Количество книг',
            ],
            [
                'attribute' => 'report_year',
                'label' => 'Год',
            ],
        ],
    ]); ?>

</div>
