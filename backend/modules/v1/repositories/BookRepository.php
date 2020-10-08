<?php

namespace backend\modules\v1\repositories;

use Yii;
use backend\modules\v1\resource\BookResource;
use yii\data\ {
    ActiveDataProvider,
    ActiveDataFilter
};
use yii\helpers\Url;
use yii\caching\TagDependency;
/**
 * This is the repository class for table "book".
 *
 */
class BookRepository
{
    /**
     * {@var BookResource}
     */

    protected $book;

    /**
     * BookRepository constructor
     * @param Book $book
     */
    public function __construct(BookResource $book)
    {
        $this->book = $book;
    }

    /**
     * save data
     */

    public function save($data)
    {
        $model = $this->book;
        $model->load($data, '');

        if ($model->save()){
            $response = Yii::$app->getResponse();
            $response->setStatusCode(201);
            $id = implode(',', array_values($model->getPrimaryKey(true)));
            $response->getHeaders()->set('Location', Url::toRoute(["book/view", 'id' => $id], true));
        }
        return $model;
    }

    /**
     * update data
     */

    public function update($model, $data)
    {
        $model->load($data, '');
        $model->save();
        return $model;
    }

    /**
     * view data
     */

    public function getById($id)
    {
        /**
         * 10 second expired
         */
        $result =  Yii::$app->db->cache(function ($db) use ($id) {
            return $this->book::findOne($id);        
        }, 1000, new TagDependency(['tags' => 'cache_table_book']));

        return ($result) ? $result : Yii::$app->getResponse()->setStatusCode(404);
    }

    /**
     * view data
     */

    public function delete($model)
    {
        $model->delete();
        Yii::$app->getResponse()->setStatusCode(204);
    }

    /**
     * get all book and filter by params filter[colomname] see BookResource->field()
     */
    public function getAllBook()
    {

        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }

        $filter = new ActiveDataFilter([
            'searchModel' => 'backend\modules\v1\models\BookSearch'
        ]);
        
        $filterCondition = null;
        
        // You may load filters from any source. For example,
        // if you prefer JSON in request body,
        // use Yii::$app->request->getBodyParams() below:
        if ($filter->load($requestParams)) { 
            $filterCondition = $filter->build();
            if ($filterCondition === false) {
                // Serializer would get errors out of it
                return $filter;
            }
        }
        $query = $this->book::find();

        if ($filterCondition !== null) {
            $query->andWhere($filterCondition);
        }

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'params' => $requestParams,
            ],
            'sort' => [
                'params' => $requestParams
            ],
        ]);
        /**
         * cache query result, expired 10 second
         */
        $query =  Yii::$app->db->cache(function ($db) use($provider){
            return $provider->prepare();   
        }, 1000, new TagDependency(['tags' => 'cache_table_book']));

        

        return $provider;
    }
}
