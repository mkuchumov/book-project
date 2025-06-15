<?php

namespace app\components;

use Yii;
use yii\base\Component;

/**
 * SmsSender — компонент для отправки SMS через smspilot.ru
 */
class SmsSender extends Component
{
    /**
     * Отправляет SMS на указанный номер
     *
     * @param string $phone Номер телефона получателя
     * @param string $message Текст сообщения
     * @return bool true, если SMS успешно отправлено, иначе false
     */
    public function send($phone, $message)
    {
        $apiKey = Yii::$app->params['smspilot.apikey'];

        $url = "https://smspilot.ru/api.php";

        $data = [
            'apikey' => $apiKey,
            'to' => $phone,
            'from' => 'BOOKCATALOG',
            'text' => $message,
        ];

        Yii::info("Попытка отправки SMS:\n" .
            "Телефон: {$phone}\n" .
            "Сообщение: {$message}\n" .
            "API-ключ: " . ($apiKey === 'EMULATOR_KEY' ? 'ТЕСТОВЫЙ РЕЖИМ' : 'РЕАЛЬНЫЙ КЛЮЧ'), 'sms');

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url, ['query' => $data]);

            $statusCode = $response->getStatusCode();
            $body = $response->getBody()->getContents();

            Yii::info("SMS отправлено успешно.\n" .
                "Статус: {$statusCode}\n" .
                "Ответ сервера: {$body}", 'sms');

            return $statusCode === 200;
        } catch (\Exception $e) {
            Yii::error("Ошибка при отправке SMS:\n" .
                "Телефон: {$phone}\n" .
                "Сообщение: {$message}\n" .
                "Ошибка: " . $e->getMessage(), 'sms');

            return false;
        }
    }
}
