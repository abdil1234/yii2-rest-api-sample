<?php

namespace backend\modules\v1\models;

use Yii;

/**
 * BookSearch represents the model behind the search form about `Book`.
 */
class BookSearch extends Book
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'author'], 'string'],
            [['id'], 'integer'],
        ];
    }
}
