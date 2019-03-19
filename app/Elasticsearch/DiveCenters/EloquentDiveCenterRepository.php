<?php
/**
 * Created by PhpStorm.
 * User: prakhar
 * Date: 1/3/18
 * Time: 4:34 PM
 */

namespace App\Elasticsearch\DiveCenters;

use Illuminate\Database\Eloquent\Collection;
use App\Scubaya\model\ManageDiveCenter;

class EloquentDiveCenterRepository implements DiveCenterRepository
{
    public function search($query = ""): Collection
    {
        /*return ManageDiveCenter::join('website_details', 'manage_dive_centers.id', '=', 'website_details.website_id')
                                ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                                ->where('doc.status', MERCHANT_STATUS_APPROVED)
                                ->where('website_details.website_type', DIVE_CENTER)
                                ->where('manage_dive_centers.country','like',$query.'%')
                                ->select('manage_dive_centers.*')
                                ->get();*/

        return ManageDiveCenter::where('status', PUBLISHED)
            ->where('country','like',$query.'%')
            ->get();
    }
}