<?php
/**
 * Created by PhpStorm.
 * User: prakhar
 * Date: 21/2/18
 * Time: 5:18 PM
 */

namespace App\Elasticsearch\Hotels;


use App\Scubaya\model\Hotel;
use Illuminate\Database\Eloquent\Collection;
use Elasticsearch\Client;

class ElasticsearchHotelRepository implements HotelsRepository
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
        $instance = new Hotel();

        $items = $this->search->search([
            'index'=> $instance->getSearchType(),
            'type' => $instance->getSearchType(),
            'size' =>  7,
            'body' => [
                'query' => [
                    'multi_match' => [
                        'query'     =>  $query,
                        'type'      =>  "phrase_prefix",
                        'fields'    =>  ['country^4', 'name', 'city^3', 'state^2']
                    ]
                ]
            ]
        ]);

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
            $hotels = new Hotel();
            $hotels->newInstance($r['_source'], true);
            $hotels->setRawAttributes($r['_source'], true);
            return $hotels;
        }, $result));
    }
}


