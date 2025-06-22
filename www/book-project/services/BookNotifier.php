<?php

namespace app\services;

use app\models\Book;
use app\models\Subscription;
use Yii;

/**
 * BookNotifier service
 */
class BookNotifier
{
    /**
     * Уведомляет всех подписчиков автора о новой книге
     *
     * @param Book $book
     */
    public function notifySubscribersAboutNewBook(Book $book): void
    {
        foreach ($book->authors as $author) {
            $subs = Subscription::find()
                ->where(['author_id' => $author->id])
                ->all();

            foreach ($subs as $sub) {
                Yii::$app->smsSender->send(
                    $sub->guest_phone,
                    "Новая книга от {$author->name}: {$book->title}"
                );
            }
        }
    }
}
