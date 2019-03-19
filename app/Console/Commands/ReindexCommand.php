<?php

namespace App\Console\Commands;

use App\Scubaya\model\Destinations;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\ManageDiveCenter;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scubaya:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all Hotels, destinations and dive centers to elastic search';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    private $search;


    public function __construct(Client $search)
    {
        parent::__construct();
        $this->search   =   $search;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Indexing all Hotels, Destinations & Dive Centers. Might take a while...');

        /*$hotels         =   Hotel::join('website_details', 'hotels_general_information.id', '=', 'website_details.website_id')
                                        ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                                        ->where('doc.status', 'approved')
                                        ->where('website_details.website_type', '=', HOTEL)
                                        ->select('hotels_general_information.*')
                                        ->get();*/

        $hotels =   Hotel::where('status', '=', 'published')->get();

        /*indexing for the hotel*/
        foreach ($hotels as $hotel){
            $this->search->index([
                'index' => $hotel->getSearchIndex(),
                'type'  => $hotel->getSearchType(),
                'id'    => $hotel->id,
                'body'  => $hotel->toSearchArray(),
            ]);

            $this->output->write('Indexing Hotels');
            $this->output->write('.');
        }

        $dive_center_query      =   new ManageDiveCenter();
        /*$dive_centers           =   $dive_center_query->join('website_details', 'manage_dive_centers.id', '=', 'website_details.website_id')
                                                      ->join('website_details_x_documents as doc','website_details.id','doc.website_detail_id')
                                                      ->where('doc.status', 'approved')
                                                      ->where('website_details.website_type', 2)
                                                      ->select('manage_dive_centers.*')
                                                      ->get();*/

        $dive_centers   =   ManageDiveCenter::where('status', '=', 'published')->get();

        /*indexing for the dive center*/
        if($dive_centers){
            foreach ($dive_centers as $dive_center){
                $this->search->index([
                    'index' =>  $dive_center_query->getTable(),
                    'type'  =>  $dive_center_query->getTable(),
                    'id'    =>  $dive_center->id,
                    'body'  =>  $dive_center->toArray()
                ]);
                $this->output->write('Indexing Dive Centers');
                $this->output->write('.');
            }
        }

        $destinations   =   Destinations::where('active', 1)->get();

        /* indexing for the destinations */
        if($destinations){
            foreach ($destinations as $destination){
                $this->search->index([
                    'index' =>  $destination->getSearchIndex(),
                    'type'  =>  $destination->getSearchType(),
                    'id'    =>  $destination->id,
                    'body'  =>  $destination->toSearchArray()
                ]);
                $this->output->write('Indexing Destinations');
                $this->output->write('.');
            }
        }

        $this->info("\nDone!");
    }
}
