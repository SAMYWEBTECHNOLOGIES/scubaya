<?php
/**
 * Created by PhpStorm.
 * User: siddharth
 * Date: 14/3/18
 * Time: 12:33 PM
 */

namespace App\Scubaya\Helpers;


class CompressImage
{
    protected $file, $path, $filename;

    /**
     * CompressImage constructor.
     */
    public function __construct()
    {
    }

    public function compressImage($file,$path,$filename){

        $info = getimagesize($file);

        if ($info['mime'] == "image/jpg") {
            $image = imagecreatefromjpg($file);
        }
        elseif ($info['mime'] == 'image/jpeg')
        {
            $image = imagecreatefromjpeg($file);
        }
        elseif ($info['mime'] == 'image/gif')
        {
            $image = imagecreatefromgif($file);
        }
        elseif ($info['mime'] == 'image/png')
        {
            $image = imagecreatefrompng($file);
        }
        else
        {
            die('Unknown image file format');
        }
        imagejpeg($image,$path.'/'.$filename,75);
    }

}