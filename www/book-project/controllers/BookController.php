<?php

namespace app\controllers;

use app\models\Book;
use app\models\BookSearch;
use app\models\Subscription;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Author model.
 */
class BookController extends Controller
{
    /**
     * @return array[]
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Только авторизованные пользователи
                    ],
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?'], // Гости
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new BookSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionCreate()
    {
        $model = new Book();

        if ($model->load(Yii::$app->request->post())) {
            $model->coverImageFile = UploadedFile::getInstance($model, 'coverImageFile');
            $model->authorIds = Yii::$app->request->post('Book')['authorIds'];

            if ($model->validate()) {
                if ($model->save(false)) {

                    // Уведомление подписчиков
                    foreach ($model->authors as $author) {
                        $subs = Subscription::find()
                            ->where(['author_id' => $author->id])
                            ->all();

                        foreach ($subs as $sub) {
                            Yii::$app->smsSender->send(
                                $sub->guest_phone,
                                "Новая книга от {$author->name}: {$model->title}"
                            );
                        }
                    }

                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = Book::findOne($id);
        return $this->render('view', ['model' => $model]);
    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->authorIds = $model->getAuthorIds();

        if ($model->load(Yii::$app->request->post())) {
            $model->coverImageFile = UploadedFile::getInstance($model, 'coverImageFile');

            if ($model->validate()) {
                if ($model->save(false)) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        Book::findOne($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('Запрашиваемая книга не найдена.');
    }
}
