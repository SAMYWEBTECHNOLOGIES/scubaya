<?php

namespace App\Scubaya\model;

use Illuminate\Database\Eloquent\Model;

class HomePageContent extends Model
{
    protected $fillable =   ['subscription_content','blog_content','features_content'];


    public static function saveHomepageContent($data)
    {

        $check = HomePageContent::where('id', 1);

        if ($check->exists($data)) {

            $homePageContent           =   HomePageContent::find(1);
            foreach($data as $key=>$value)
            {
                $homePageContent->$key  =   $value;
            }

            $homePageContent->update();
        }
        else{

            $homePageContent    =   new HomePageContent();
            foreach($data as $key=>$value)
            {
                $homePageContent->$key  =   $value;
            }
            $homePageContent->save();
        }

    }
}
