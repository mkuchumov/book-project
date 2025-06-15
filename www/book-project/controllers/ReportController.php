<?php

namespace app\controllers;

use Yii;
use yii\data\ArrayDataProvider;
use yii\web\Controller;

/**
 * ReportController
 */
class ReportController extends Controller
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionTopAuthors()
    {
        $year = Yii::$app->request->get('year', date('Y'));

        // Проверяем корректность года
        if (!is_numeric($year) || $year < 1900 || $year > date('Y')) {
            $year = date('Y');
        }

        $sql = "
            SELECT a.id, a.name, COUNT(ba.book_id) AS books_count, :year AS report_year
            FROM author a
            JOIN book_author ba ON a.id = ba.author_id
            JOIN book b ON ba.book_id = b.id AND b.year = :year
            GROUP BY a.id, a.name
            ORDER BY books_count DESC
            LIMIT 10
        ";

        $rows = Yii::$app->db->createCommand($sql)
            ->bindValue(':year', $year)
            ->queryAll();

        $dataProvider = new ArrayDataProvider([
            'allModels' => $rows,
            'pagination' => false,
        ]);

        return $this->render('top-authors', [
            'dataProvider' => $dataProvider,
            'year' => $year,
        ]);
    }
}
