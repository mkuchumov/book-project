<?php

namespace app\controllers;

use app\models\Author;
use app\models\Book;
use app\models\BookAuthor;
use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

/**
 * ReportController отвечает за генерацию отчётов
 */
class ReportController extends Controller
{
    /**
     * Экшн выводит список топ-10 авторов по количеству книг за указанный год.
     *
     * @return mixed
     */
    public function actionTopAuthors()
    {
        $year = $this->validateYear(Yii::$app->request->get('year'));

        $authors = Author::find()
            ->select([
                Author::tableName() . '.id',
                Author::tableName() . '.name',
                'COUNT(' . BookAuthor::tableName() . '.book_id) AS books_count',
                new \yii\db\Expression(':year AS report_year', [':year' => $year]),
            ])
            ->innerJoin(
                BookAuthor::tableName(),
                BookAuthor::tableName() . '.author_id = ' . Author::tableName() . '.id'
            )
            ->innerJoin(
                Book::tableName(),
                Book::tableName() . '.id = ' . BookAuthor::tableName() . '.book_id AND ' .
                Book::tableName() . '.year = :year',
                [':year' => $year]
            )
            ->groupBy([
                Author::tableName() . '.id',
                Author::tableName() . '.name',
            ])
            ->orderBy(['books_count' => SORT_DESC])
            ->limit(10)
            ->asArray()
            ->all();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $authors,
            'pagination' => false,
        ]);

        return $this->render('top-authors', [
            'dataProvider' => $dataProvider,
            'year' => $year,
        ]);
    }

    /**
     * Валидирует значение года.
     *
     * @param mixed $year
     * @return int
     */
    private function validateYear($year): int
    {
        if ($year === null) {
            return (int)date('Y');
        }

        $year = filter_var($year, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1900, 'max_range' => (int)date('Y')]
        ]);

        return $year ?: (int)date('Y');
    }
}
