<?php
namespace backend\modules\v1\controllers;

use Yii;
use backend\modules\v1\resource\UserResource;

/**
 * User controller
 */
class UserController extends DefaultController
{
    public $modelClass = UserResource::class;

    
    
}
