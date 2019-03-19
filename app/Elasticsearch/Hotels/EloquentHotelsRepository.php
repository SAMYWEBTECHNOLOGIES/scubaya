<?php
namespace App\Elasticsearch\Hotels;
use Illuminate\Database\Eloquent\Collection;
use App\Scubaya\model\Hotel;

class EloquentHotelsRepository implements HotelsRepository
{
    public function search($query = ""): Collection
    {
        return Hotel::where('country','like',"{$query}%")
                ->where('status', PUBLISHED)
                ->get(['id','name','country', 'address']);
    }
}