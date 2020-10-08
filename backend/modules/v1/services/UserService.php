<?php

namespace backend\modules\v1\services;

use Yii;
use backend\modules\v1\repositories\UserRepository;
use yii\web\NotFoundHttpException;

/**
 * This is the repository class for table "book".
 *
 */
class UserService
{
    /**
     * {@var UserRepository}
     */

    protected $userRepository;

    /**
     * UserService constructor
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loginUser(){
        return $this->userRepository->login(Yii::$app->getRequest()->getBodyParams());
    }

    public function registerUser(){
        return $this->userRepository->register(Yii::$app->getRequest()->getBodyParams());
    }
}
