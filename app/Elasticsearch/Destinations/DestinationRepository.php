<?php

namespace App\Elasticsearch\Destinations;

use Illuminate\Database\Eloquent\Collection;

interface DestinationRepository
{
    public function search($query = ""): Collection ;
}