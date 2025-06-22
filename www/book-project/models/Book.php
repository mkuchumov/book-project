<?php

namespace app\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $description
 * @property string $isbn
 * @property string|null $cover_image
 *
 * @property Author[] $authors
 * @property BookAuthor[] $bookAuthors
 */
class Book extends \yii\db\ActiveRecord
{
    /**
     * @var array|int[] $authorIds ID авторов для формы
     */
    public $authorIds = [];

    /**
     * @var UploadedFile|null Загружаемое изображение обложки
     */
    public $coverImageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'year', 'isbn'], 'required', 'message' => 'Поле не может быть пустым'],
            [['year'], 'integer'],
            [['description'], 'string'],
            [['title', 'isbn'], 'string', 'max' => 100],
            [['cover_image'], 'string', 'max' => 255],
            ['isbn', 'unique'],
            [['coverImageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['authorIds'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'title' => 'Название',
            'year' => 'Год',
            'description' => 'Описание',
            'isbn' => 'ISBN',
            'cover_image' => 'Обложка',
            'coverImageFile' => 'Загрузить обложку',
            'authorIds' => 'Авторы',
        ];
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors()
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_author', ['book_id' => 'id']);
    }

    /**
     * Gets query for [[BookAuthors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookAuthors()
    {
        return $this->hasMany(BookAuthor::class, ['book_id' => 'id']);
    }

    /**
     * Загружает и сохраняет изображение обложки
     * @return void
     */
    public function uploadCoverImage()
    {
        if ($this->coverImageFile instanceof UploadedFile) {
            $filename = 'uploads/books/' . $this->coverImageFile->baseName . '_' . time() . '.'
                . $this->coverImageFile->extension;

            $this->coverImageFile->saveAs($filename);

            $this->cover_image = $filename;
        }
    }

    /**
     * Сохраняет книгу вместе с привязкой к авторам
     *
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \Throwable
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();

        try {
            if ($runValidation && !$this->validate()) {
                return false;
            }

            $this->uploadCoverImage();

            // Сохраняем саму книгу
            if (!parent::save($runValidation, $attributeNames)) {
                $transaction->rollBack();
                return false;
            }

            // Если это новый объект — сохраняем связи после создания
            if ($this->isNewRecord) {
                foreach ($this->authorIds as $authorId) {
                    $bookAuthor = new BookAuthor();
                    $bookAuthor->book_id = $this->id;
                    $bookAuthor->author_id = $authorId;

                    if (!$bookAuthor->save()) {
                        $transaction->rollBack();
                        return false;
                    }
                }

                $transaction->commit();
                return true;
            }

            // Получаем текущие связи из БД
            $existingRelations = BookAuthor::find()
                ->where(['book_id' => $this->id])
                ->indexBy('author_id')
                ->all();

            $existingAuthorIds = array_keys($existingRelations);

            // Разбиваем на добавление и удаление
            $toAdd = array_diff($this->authorIds, $existingAuthorIds);
            $toDelete = array_diff($existingAuthorIds, $this->authorIds);

            // Удаление лишних связей
            if (!empty($toDelete)) {
                if (!BookAuthor::deleteAll([
                    'book_id' => $this->id,
                    'author_id' => $toDelete,
                ])) {
                    $transaction->rollBack();
                    return false;
                }
            }

            // Добавление новых связей
            foreach ($toAdd as $authorId) {
                $bookAuthor = new BookAuthor();
                $bookAuthor->book_id = $this->id;
                $bookAuthor->author_id = $authorId;

                if (!$bookAuthor->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }

            $transaction->commit();
            return true;

        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e; // Перебрасываем исключение для логирования/обработки
        }
    }

    /**
     * Возвращает список ID авторов для формы
     *
     * @return array
     */
    public function getAuthorIds()
    {
        if ($this->authorIds === []) {
            return $this->getAuthors()->select('id')->column();
        }

        return $this->authorIds;
    }

    /**
     * Устанавливает авторов из формы
     * @param $value
     * @return void
     */
    public function setAuthorIds($value)
    {
        $this->authorIds = is_array($value) ? array_map('intval', $value) : [];
    }
}
