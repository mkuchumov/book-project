<?php

namespace app\controllers;

use app\models\SubscriptionForm;
use Yii;
use yii\web\Controller;

/**
 * SubscriptionController
 */
class SubscriptionController extends Controller
{
    public function actionSubscribe($author_id)
    {
        $form = new SubscriptionForm();
        $form->author_id = $author_id;

        if ($form->load(Yii::$app->request->post()) && $form->subscribe()) {
            Yii::$app->session->setFlash('success', 'Вы успешно подписаны!');
            return $this->redirect(['author/view', 'id' => $author_id]);
        }

        return $this->render('subscribe', [
            'model' => $form,
        ]);
    }
}
