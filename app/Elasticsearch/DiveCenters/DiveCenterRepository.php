<?php
/**
 * Created by PhpStorm.
 * User: prakhar
 * Date: 1/3/18
 * Time: 4:35 PM
 */

namespace App\Elasticsearch\DiveCenters;

use Illuminate\Database\Eloquent\Collection;

interface DiveCenterRepository
{
    public function search($query = ""): Collection ;
}