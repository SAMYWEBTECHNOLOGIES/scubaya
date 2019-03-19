<?php

namespace App\Elasticsearch\Destinations;

use App\Scubaya\model\Destinations;
use Illuminate\Database\Eloquent\Collection;

class EloquentDestinationRepository implements DestinationRepository
{
    public function search($query = ""): Collection
    {
        return Destinations::where('country','like',"{$query}%")
                            ->where('active', 1)
                            ->get(['id','name','country', 'location']);
    }
}