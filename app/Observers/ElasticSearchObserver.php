<?php
/**
 * Created by PhpStorm.
 * User: Surbhi
 * Date: 8/9/18
 * Time: 3:41 PM
 */

namespace App\Observers;


use Elasticsearch\Client;

class ElasticSearchObserver
{
    private $elasticsearch;

    public function __construct(Client $elasticsearch)
    {
        $this->elasticsearch = $elasticsearch;
    }

    public function created($model)
    {
        $this->elasticsearch->index([
            'index' => $model->getSearchIndex(),
            'type'  => $model->getSearchType(),
            'id'    => $model->id,
            'body'  => $model->toSearchArray(),
        ]);
    }

    public function updated($model)
    {
        $this->elasticsearch->index([
            'index' => $model->getSearchIndex(),
            'type'  => $model->getSearchType(),
            'id'    => $model->id,
            'body'  => $model->toSearchArray(),
        ]);
    }

    public function saved($model)
    {
        $this->elasticsearch->index([
            'index' => $model->getSearchIndex(),
            'type'  => $model->getSearchType(),
            'id'    => $model->id,
            'body'  => $model->toSearchArray(),
        ]);
    }

    public function deleted($model)
    {
        $this->elasticsearch->delete([
            'index' => $model->getSearchIndex(),
            'type'  => $model->getSearchType(),
            'id'    => $model->id,
        ]);
    }
}