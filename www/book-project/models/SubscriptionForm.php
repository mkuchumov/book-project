<?php

namespace app\models;

use yii\base\Model;

/**
 *
 */
class SubscriptionForm extends Model
{
    public $author_id;
    public $phone;

    public function rules()
    {
        return [
            [['author_id', 'phone'], 'required'],
            ['phone', 'string', 'max' => 20],
            ['author_id', 'exist', 'targetClass' => Author::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * @return string[]
     */
    public function attributeLabels()
    {
        return [
            'phone' => 'Ваш телефон',
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function subscribe()
    {
        if (!$this->validate()) {
            return false;
        }

        $subscription = new Subscription();
        $subscription->author_id = $this->author_id;
        $subscription->guest_phone = $this->phone;
        $subscription->created_at = time();

        return $subscription->save();
    }
}
