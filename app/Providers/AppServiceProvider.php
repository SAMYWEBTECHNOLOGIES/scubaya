<?php

namespace App\Providers;

use App\Elasticsearch\Destinations\DestinationRepository;
use App\Elasticsearch\Destinations\ElasticSearchDestinationRepository;
use App\Elasticsearch\DiveCenters\DiveCenterRepository;
use App\Elasticsearch\DiveCenters\ElasticSearchDiveCenterRepository;
use App\Elasticsearch\DiveCenters\EloquentDestinationRepository;
use App\Elasticsearch\DiveCenters\EloquentDiveCenterRepository;
use App\Elasticsearch\Hotels\ElasticsearchHotelRepository;
use App\Elasticsearch\Hotels\EloquentHotelsRepository;
use App\Elasticsearch\Hotels\HotelsRepository;
use App\Scubaya\model\Hotel;
use App\Scubaya\model\ManageDiveCenter;
use Elasticsearch\ClientBuilder;
use Validator;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\DuskServiceProvider;
use Illuminate\Support\Facades\Schema;
use Elasticsearch\Client;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Hotel::observe($this->app->make(ElasticsearchHotelRepository::class));
        ManageDiveCenter::observe($this->app->make(ElasticSearchDiveCenterRepository::class));

        Schema::defaultStringLength(191);

        /* custom validation to check image count for room gallery */
        Validator::extend('image_upload_count', 'App\Http\Validation\CustomValidator@validateImageUploadCount');

        /* custom validation to check room number already exists or not */
        Validator::extend('room_number_exists', 'App\Http\Validation\CustomValidator@validateRoomNumber');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }

        /* elastic search binding for hotels */
        $this->app->singleton(HotelsRepository::class, function($app) {
            if (!config('services.search.enabled')) {
                return new EloquentHotelsRepository();
            }
            return new ElasticsearchHotelRepository(
                $app->make(Client::class)
            );
        });

        /* elastic search binding for dive centers */
        $this->app->singleton(DiveCenterRepository::class, function($app) {
            if (!config('services.search.enabled')) {
                return new EloquentDiveCenterRepository();
            }
            return new ElasticSearchDiveCenterRepository(
                $app->make(Client::class)
            );
        });

        /* elastic search binding for destination */
        $this->app->singleton(DestinationRepository::class, function($app) {
            if (!config('services.search.enabled')) {
                return new \App\Elasticsearch\Destinations\EloquentDestinationRepository();
            }
            return new ElasticSearchDestinationRepository(
                $app->make(Client::class)
            );
        });

        $this->bindSearchClient();
    }

    private function bindSearchClient()
    {
        $this->app->bind(Client::class, function ($app) {
            return ClientBuilder::create()
                ->setHosts(config('services.search.hosts'))
                ->build();
        });
    }
}
