<?php
/**
 * Created by PhpStorm.
 * User: prakhar
 * Date: 21/2/18
 * Time: 3:30 PM
 */

namespace App\Elasticsearch\Hotels;
use Illuminate\Database\Eloquent\Collection;

interface HotelsRepository
{
    public function search($query = ""): Collection;
}