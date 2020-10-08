<?php

namespace backend\modules\v1\services;

use backend\modules\v1\resource\BookResource;
use Yii;
use backend\modules\v1\repositories\BookRepository;
use yii\web\NotFoundHttpException;

/**
 * This is the repository class for table "book".
 *
 */
class BookService
{
    /**
     * {@var BookRepository}
     */

    protected $bookRepository;

    /**
     * BookService constructor
     * @param BookRepository $bookRepository
     */
    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function saveBookData(){
        
        return $this->bookRepository->save(Yii::$app->getRequest()->getBodyParams());
    }

    public function updateBookData($id){
        $model = $this->getById($id);
        if($model instanceof BookResource){
            return $this->bookRepository->update($model, Yii::$app->getRequest()->getBodyParams());
        }else{
            throw new NotFoundHttpException("book id:$id  not found.");
        }

    }

    public function getAllBook(){
        
        return $this->bookRepository->getAllBook();
    }

    public function getById($id){
        return $this->bookRepository->getById($id);
    }

    public function deleteBookData($id){
        $model = $this->getById($id);
        
        if($model instanceof BookResource){
            return $this->bookRepository->delete($model);
        }else{
            throw new NotFoundHttpException("book id:$id not found.");
        }
    }
}
