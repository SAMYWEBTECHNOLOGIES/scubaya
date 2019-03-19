<?php
/**
 * Created by PhpStorm.
 * User: prakhar
 * Date: 27/2/18
 * Time: 4:55 PM
 */

namespace App\Elasticsearch\DiveCenters;


use App\Scubaya\model\ManageDiveCenter;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ElasticSearchDiveCenterRepository implements DiveCenterRepository
{
    private $search;

    public function __construct(Client $client)
    {
        $this->search = $client;
    }

    public function search($query = ""): Collection
    {
        $items = $this->searchOnElasticsearch($query);

        return $this->buildCollection($items);
    }

    private function searchOnElasticsearch($query)
    {
        $instance = new ManageDiveCenter();
        if(strlen($query)>0){

            $items = $this->search->search([
                'index'     => $instance->getSearchType(),
                'type'      => $instance->getSearchType(),
                /*'size'      =>  7,*/
                'body' => [
                    'query' => [
                        'multi_match' => [
                            'query'     =>  $query,
                            'type'      =>  "phrase_prefix",
                            'fields'    =>  ['country^4','name','city^3','state^2']
                        ]
                    ]
                ]
            ]);
        }else{
            $items = $this->search->search([
                'index'     => $instance->getSearchType(),
                'type'      => $instance->getSearchType(),
                'body' => [
                    'query' => [
                        'match_all' =>  ['boost'    =>  1.2]
                    ]
                ]
            ]);

        }

        return $items;
    }

    private function buildCollection(array $items): Collection
    {
        /**
         * The data comes in a structure like this:
         *
         * [
         *      'hits' => [
         *          'hits' => [
         *              [ '_source' => 1 ],
         *              [ '_source' => 2 ],
         *          ]
         *      ]
         * ]
         *
         * And we only care about the _source of the documents.
         */
        $result = $items['hits']['hits'];

        return Collection::make(array_map(function($r) {
            $article = new ManageDiveCenter();
            $article->newInstance($r['_source'], true);
            $article->setRawAttributes($r['_source'], true);
            return $article;
        }, $result));
    }
}