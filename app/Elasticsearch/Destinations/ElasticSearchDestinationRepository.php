<?php

namespace App\Elasticsearch\Destinations;

use App\Scubaya\model\Destinations;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;

class ElasticSearchDestinationRepository implements DestinationRepository
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
        $instance = new Destinations();

        if(strlen($query)>0) {
            $items = $this->search->search([
                'index'     => $instance->getSearchType(),
                'type'      => $instance->getSearchType(),
                'body' => [
                    'query' => [
                        'multi_match' => [
                            'query'     =>  $query,
                            'type'      =>  "phrase_prefix",
                            'fields'    =>  ['country^4','name']
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
                        'match_all' =>  ['boost' =>  1.2]
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
            $article = new Destinations();
            $article->newInstance($r['_source'], true);
            $article->setRawAttributes($r['_source'], true);
            return $article;
        }, $result));
    }
}