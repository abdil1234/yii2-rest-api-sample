<?php
namespace backend\modules\v1\resource;

use common\models\User;
use yii\helpers\Url;


class UserResource extends User
{

    public function fields()
    {
        return ['id', 'email', 'username', 'status'];
    }
}