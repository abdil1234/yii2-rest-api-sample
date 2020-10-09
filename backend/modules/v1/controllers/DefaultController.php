<?php

namespace backend\modules\v1\controllers;
use yii\rest\Controller;
use common\models\User;
use yii\filters\auth\HttpBearerAuth;

/**
 * Default controller for the `v1` module
 */
class DefaultController extends Controller
{
    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'data',
    ];

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className()
        ];
        return $behaviors;
    }

}
