<?php
namespace backend\controllers;

use Yii;
use yii\rest\ActiveController;
use backend\resource\UserResource;

/**
 * User controller
 */
class UserController extends ActiveController
{
    public $modelClass = UserResource::class;
    
}
