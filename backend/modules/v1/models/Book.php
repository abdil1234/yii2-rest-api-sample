<?php

namespace backend\modules\v1\models;

use Yii;
use yii\caching\TagDependency;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property string $author
 * @property int $created_at
 * @property int $updated_at
 */
class Book extends \yii\db\ActiveRecord
{
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
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'author'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['title', 'author'], 'string', 'max' => 255],
            [['title'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'author' => 'Author',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    protected function flushTableCache(){
        TagDependency::invalidate(Yii::$app->cache, 'cache_table_book');
    }

    public function afterDelete(){
        $this->flushTableCache();
    }

    public function afterSave($insert, $changedAttributes){
        $this->flushTableCache();
    }
}
