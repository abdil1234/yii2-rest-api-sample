<?php
namespace backend\modules\v1\controllers;

use Yii;
use backend\modules\v1\services\UserService;

/**
 * User controller
 */
class UserController extends DefaultController
{
    protected $userService;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verb'] =  [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'login' => ['POST'],
                'register' => ['POST'],
            ]
        ];
        return $behaviors;
    }

    public function __construct($id, $module, UserService $userService, $config = [])
    {
        $this->userService = $userService;
        parent::__construct($id, $module, $config);
    }

    public function actionLogin(){
        return $this->userService->loginUser();

    }

    public function actionRegister(){
        return $this->userService->registerUser();
    }
}
