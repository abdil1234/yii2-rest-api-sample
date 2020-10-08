<?php
namespace backend\modules\v1\resource;

use Yii;
use yii\web\Link; // represents a link object as defined in JSON Hypermedia API Language.
use yii\web\Linkable;
use backend\modules\v1\models\Book;
use yii\helpers\Url;

class BookResource extends Book implements Linkable
{

    public function fields()
    {
        return ['id', 'title', 'description', 'author'];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['book/view', 'id' => $this->id], true),
            'update' => Url::to(['book/update', 'id' => $this->id], true),
            'delete' => Url::to(['book/delete', 'id' => $this->id], true),
            'index' => Url::to(['book/index'], true),
        ];
    }
}