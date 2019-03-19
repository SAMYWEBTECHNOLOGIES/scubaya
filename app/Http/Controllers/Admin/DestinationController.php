<?php

namespace App\Http\Controllers\Admin;

use App\Scubaya\Helpers\CompressImage;
use App\Scubaya\model\CurrencyExchange;
use App\Scubaya\model\Destinations;
use App\Scubaya\model\MarineLife;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class DestinationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data           =   Destinations::all(['image','name','country','geographical_area','water_temp','weather','active','id']);
        return view('admin.manage.destination.index',['data'    =>  $data]);
    }

    protected function __prepare($request)
    {
        $destinationtips    =   [];

        /* For season info */
        $season         = array('no_dive_season'=>$request->get('no_dive_season',0),'whole_year'=> $request->get('whole_year',0));
        $season['info'] = array(
            'from' => [
                $request->season_from  => $request->get('season_from_state',0)
            ],
            'till' => [
                $request->season_till  => $request->get('season_till_state',0)
            ]
        );

        /* For exposure season info */
        $exposure_season = array('no_exposure'=>$request->get('no_exposure',0),'whole_year'=>$request->get('whole_year_exposure',0));
        $exposure_season['info']    = array(
            'from' => [
                $request->exposure_from  => $request->get('exposure_from_state',0)
            ],
            'till' => [
                $request->exposure_till  => $request->get('exposure_till_state',0)
            ]
        );

        /* For rain season info */
        $rain_season = array('no_rain'=>$request->get('no_rain',0),'whole_year'=>$request->get('whole_year_rain',0));
        $rain_season['info']  = array(
            'from' => [
                $request->rain_from  => $request->get('rain_from_state',0)
            ],
            'till' => [
                $request->rain_till  => $request->get('rain_till_state',0)
            ]
        );

        /* For Tips info */
        for($j=1;$j<count($request->tips_title)+1;$j++){
            array_push($destinationtips,['label'=>$request->tips_title[$j],'description'=>$request->tips_information[$j]]);
        }

        $data = [
            'active'                        =>  $request->active,
            'is_sub_destination'            =>  $request->is_sub_destination,
            'name'                          =>  $request->destination_name,
            'sub_name'                      =>  $request->destination_sub_name,
            'is_subdestination_of'          =>  $request->is_subdestination_of,
            'language_spoken'               =>  json_encode($request->language_spoken),
            'country'                       =>  $request->country,
            'location'                      =>  $request->location,
            'latitude'                      =>  $request->latitude,
            'longitude'                     =>  $request->longitude,
            'geographical_area'             =>  $request->geographical_area,
            'region'                        =>  $request->region,
            'water_temperature'             =>  $request->get('water_temp',0),
            'weather'                       =>  $request->get('weather',0),
            'voltage'                       =>  $request->electric_voltage,
            'country_currency'              =>  $request->country_currency,
            'accepted_currency'             =>  json_encode($request->accepted_country_currency),
            'short_description'             =>  $request->add_short_description,
            'long_description'              =>  $request->add_long_description,
            'dive_description'              =>  $request->add_dive_description,
            'tourist_description'           =>  $request->add_tourist_description,
            'time_zone'                     =>  $request->time_zone,
            'rs_floor'                      =>  $request->reef_sea_floor,
            'macro'                         =>  $request->macro,
            'pelagic'                       =>  $request->pelagic,
            'wreck'                         =>  $request->wreck,
            'season'                        =>  json_encode($season),
            'exposure_season'               =>  json_encode($exposure_season),
            'rain_season'                   =>  json_encode($rain_season),
            'population'                    =>  $request->population,
            'religion'                      =>  $request->religion,
            'capital_wikipedia'             =>  $request->capital_wikipedia,
            'map_decompression_chambers'    =>  json_encode($request->decompression),
            'climate'                       =>  $request->climate,
            'hdi_rank'                      =>  $request->hdi_rank,
            'phone_code'                    =>  $request->phone_code,
            'water_temp'                    =>  json_encode($request->watertemp),
            'rain_fall_temp'                =>  json_encode($request->rainfalltemp),
            'min_air_temp'                  =>  json_encode($request->minairtemp),
            'max_air_temp'                  =>  json_encode($request->maxairtemp),
            'destination_tips'              =>  json_encode($destinationtips),
            'visa_countries'                =>  json_encode($request->selector_country),
            'tipping'                       =>  $request->tipping,
        ];

        $file               =   $request->file('image');
        $galleryImageFiles  =   $request->file('images');

        if($file){
            $data['image']   =   $file->getClientOriginalName();
        }

        if(count($galleryImageFiles) > 0){
            $images =   array();

            foreach($galleryImageFiles as $file){
                array_push($images, $file->getClientOriginalName());
            }
            $data['images']  =   json_encode($images);
        }

        return $data;
    }

    public function addDestination(Request $request)
    {
        if($request->isMethod('post')){

            $data           =   $this->__prepare($request);
            $destination    =   Destinations::saveDestination($data);

            // save room profile image
            if($request->file('image')){
                $this->_saveDestinationImageInLocalDirectory($request->file('image'), $destination->image, $destination->id);
            }

            //save images for gallery
            if($request->file('images')){
                $this->_savesDestinationGalleryInLocalDirectory($request->file('images'), $destination->id);
            }

            return redirect()->route('scubaya::admin::manage::destinations');
        }

        $currency_all       =  DB::table('currency_all')->get(['currency_code','currency_name','symbol']);
        $destination_main   =  Destinations::where('is_sub_destination',0)->get();
        $languagesSpoken    =  DB::table('languages')->get();

        return view('admin.manage.destination.add_destination',
        ['destination_main' =>  $destination_main,'currency_all'   =>  $currency_all,'languagesSpoken'=> $languagesSpoken]);
    }

    public function editDestination(Request $request)
    {
        if($request->isMethod('post')){
            $data           =   $this->__prepare($request);
            /*delete*/
            $previous_data  =   Destinations::where('id',$request->id)->first(['image','images']);
            /*save the edit destination data*/
            $destination    =   Destinations::updateOrCreate(['id'=> $request->id],$data);

            // save room profile image
            if($request->file('image')){
                if($previous_data->image){
                    $this->_removeImageFromDirectory($request->id,$previous_data->image);
                }
                $this->_saveDestinationImageInLocalDirectory($request->file('image'), $destination->image, $destination->id);
            }

            //save images for gallery
            if($request->file('images')){
                if($previous_data->images){
                    $this->_removeGalleryFromDirectory(json_decode($previous_data->images),$request->id);
                }
                $this->_savesDestinationGalleryInLocalDirectory($request->file('images'), $destination->id);
            }
            return redirect()->route('scubaya::admin::manage::destinations');
        }

        $currency_all      = DB::table('currency_all')->get(['currency_code','currency_name','symbol']);
        $main_destinations = Destinations::where('is_sub_destination',0)->get();
        $destination       = Destinations::where('id',$request->id)->first();
        $languagesSpoken    =  DB::table('languages')->get();

        return view('admin.manage.destination.edit_destination',
                [
                    'destination'        =>  $destination,
                    'currency_all'       =>  $currency_all,
                    'main_destinations'  =>  $main_destinations,
                    'languagesSpoken'    => $languagesSpoken
                ]);
    }

    public function deleteDestination(Request $request)
    {
        $previous_data      =   Destinations::find($request->id,['image','images']);

        /*remove the image from the local Directory*/
        if($previous_data->image){
            $this->_removeImageFromDirectory($request->id,$previous_data->image);
        }

        /*remove the gallery from the local directory*/
        if($previous_data->images){
            $this->_removeGalleryFromDirectory(json_decode($previous_data->images),$request->id);
        }

        Destinations::destroy($request->id);

        $request->session()->flash('success','Destination deleted successfully');

        return redirect()->route('scubaya::admin::manage::destinations');
    }

    protected function _saveDestinationImageInLocalDirectory($file, $filename, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/destination/';
        File::makeDirectory($path, 0777, true, true);

        $filename   =   $id.'-'.$filename;
        $compressImage = new CompressImage();
        $compressImage->compressImage($file,$path,$filename);
    }

    protected function _savesDestinationGalleryInLocalDirectory($galleryImageFiles, $id)
    {
        $path     =   public_path(). '/assets/images/scubaya/destination/gallery/destination-'.$id.'/';
        File::makeDirectory($path, 0777, true, true);

        foreach($galleryImageFiles as $file){
            $filename   =   $file->getClientOriginalName();
            $compressImage = new CompressImage();
            $compressImage->compressImage($file,$path,$filename);
        }
    }

    /* remove document from local directory */
    protected function _removeImageFromDirectory($id, $filename)
    {
        $path = public_path() . '/assets/images/scubaya/destination/'.$id.'-'.$filename;
        File::delete($path);
    }

    protected function _removeGalleryFromDirectory($image,$id)
    {
        $path = public_path(). '/assets/images/scubaya/destination/gallery/destination-'.$id.'/';
        File::delete($path);
    }

    public function getAllDestinations()
    {
        $destinations   =   Destinations::where('active', 1)
                                        ->where('is_sub_destination', 0)
                                        ->get(['id','name','region', 'geographical_area','is_destination_popular']);

        return view('admin.manage.popular_destinations.index',['destinations'   =>  $destinations, 'sno' => 1]);
    }

    public function isDestinationPopular(Request $request)
    {
        $destination  =   Destinations::find($request->destination_id);

        $destination->is_destination_popular    =   $request->is_popular;
        $destination->update();
    }
}
