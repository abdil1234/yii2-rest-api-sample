<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use console\rbac\AuthorRule;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // add "author" role and give this role the "createPost" permission
        $author = $auth->createRole('user');
        $auth->add($author);

        // add "admin" role and give this role the "updatePost" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);

        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($admin, 1);
    }
}