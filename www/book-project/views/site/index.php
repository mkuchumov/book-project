<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'Каталог книг';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent mt-5 mb-5">
        <h1 class="display-4">Каталог книг</h1>

        <p class="lead">Каталог книг и авторов</p>

        <p><?=Html::a("Посмотреть книги", ["book/index"]) ?></p>
        <p><?=Html::a("Посмотреть авторов", ["author/index"]) ?></p>

        <p><?=Html::a("ТОП-10 авторов", ["report/top-authors"], ['class' => 'btn btn-lg btn-success'])?></p>

    </div>


</div>
