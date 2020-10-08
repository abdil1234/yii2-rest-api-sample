<?php
namespace backend\modules\v1\resource;

use yii\base\Model;
use yii\web\Link; // represents a link object as defined in JSON Hypermedia API Language.
use yii\web\Linkable;
use backend\modules\v1\models\User;
use yii\helpers\Url;

class UserResource extends User implements Linkable
{

    public function fields()
    {
        return ['id', 'email', 'username', 'status'];
    }

    public function extraFields()
    {
        return ['profile'];
    }

    public function getLinks()
    {
        return [
            Link::REL_SELF => Url::to(['user/view', 'id' => $this->id], true),
            'edit' => Url::to(['user/view', 'id' => $this->id], true),
            'profile' => Url::to(['user/profile/view', 'id' => $this->id], true),
            'index' => Url::to(['users'], true),
        ];
    }
}