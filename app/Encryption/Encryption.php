<?php
/**
 * Created by PhpStorm.
 * User: prakhar
 * Date: 14/5/18
 * Time: 4:57 PM
 */
namespace App\Encryption;

use Illuminate\Support\Facades\Crypt;

trait Encryption
{
    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encryptable)) {
            $value = Crypt::decrypt($value);
        }
        return $value;
    }

    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encryptable)) {
            $value = Crypt::encrypt($value);
        }

        return parent::setAttribute($key, $value);
    }
}