<?php
namespace backend\modules\v1\controllers;

use Yii;
use backend\modules\v1\services\BookService;
use yii\filters\AccessControl;
/**
 * User controller
 */
class BookController extends DefaultController
{

    protected $bookService;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['admin'],
                ],
                [
                    'allow' => true,
                    'actions' => ['index', 'view'],
                    'roles' => ['user'],
                ]
            ],
        ];
        $behaviors['verb'] =  [
            'class' => \yii\filters\VerbFilter::className(),
            'actions' => [
                'index' => ['GET', 'HEAD'],
                'view' => ['GET', 'HEAD'],
                'create' => ['POST'],
                'update' => ['PUT', 'PATCH'],
                'delete' => ['DELETE'],
            ]
        ];
        return $behaviors;
    }


    public function __construct($id, $module, BookService $bookService, $config = [])
    {
        $this->bookService = $bookService;
        parent::__construct($id, $module, $config);
    }


    public function actionIndex()
    {
        return $this->bookService->getAllBook();
    }

    public function actionCreate(){
        return $this->bookService->saveBookData();
    }

    public function actionView($id){
        return $this->bookService->getByid($id);
    }

    public function actionUpdate($id){
        return $this->bookService->updateBookData($id);
    }

    public function actionDelete($id){
        return $this->bookService->deleteBookData($id);
    }

}
