<?php

namespace app\models;

/**
 * This is the model class for table "subscription".
 *
 * @property int $id
 * @property int $author_id
 * @property string $guest_phone
 * @property int|null $created_at
 *
 * @property Author $author
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'default', 'value' => null],
            [['author_id', 'guest_phone'], 'required'],
            [['author_id', 'created_at'], 'integer'],
            [['guest_phone'], 'string', 'max' => 255],
            [
                ['author_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Author::class,
                'targetAttribute' => ['author_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
            'guest_phone' => 'Guest Phone',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Author]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }
}
