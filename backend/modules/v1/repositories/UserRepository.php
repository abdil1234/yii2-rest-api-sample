<?php

namespace backend\modules\v1\repositories;

use Yii;
use backend\modules\v1\resource\UserResource;
use backend\models\SignupForm;
use common\models\LoginForm;
use yii\caching\TagDependency;
/**
 * This is the repository class for table "user".
 *
 */
class UserRepository
{
    /**
     * {@var UserResource}
     */
    protected $user;

    /**
     * {@var SignupForm}
     */
    protected $userSignUp;

    /**
     * {@var LoginForm}
     */
    protected $userLogin;

    /**
     * UserRepository constructor
     * @param UserResource $user
     * @param SignupForm $userSignUp
     * @param LoginForm $userLogin
     */
    public function __construct(UserResource $user, SignupForm $userSignUp, LoginForm $userLogin)
    {
        $this->user = $user;
        $this->userSignUp = $userSignUp;
        $this->userLogin = $userLogin;
    }

    /**
     * register user
     */

    public function register($data)
    {
        $model = $this->userSignUp;
        $model->load($data, '');

        if ($model->signup()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
        }
        return $model;
    }

    /**
     * view data
     */

    public function login($data)
    {
        $model = $this->userLogin;
        $model->load($data, '');
        if($model->login()){
            return $this->user::findByUsername($model->username);
        }
        
        $response = Yii::$app->getResponse();
        $response->setStatusCode(401);
        return ["Incorrect username or password."];
    }
}
