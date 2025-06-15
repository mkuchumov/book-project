<?php

namespace app\components;

use yii\log\FileTarget;

class SmsLogTarget extends FileTarget
{
    /**
     * @var string
     */
    public $logFile = '@runtime/logs/sms.log';

    /**
     * @return void
     */
    public function init()
    {
        parent::init();
        $this->logFile = \Yii::getAlias($this->logFile);
    }

    /**
     * @param $message
     * @return string
     */
    public function formatMessage($message)
    {
        list($text, $level, $category, $timestamp) = $message;

        $time = date('Y-m-d H:i:s', (int) $timestamp);

        return "[{$time}] [SMS]\n{$text}\n";
    }
}
