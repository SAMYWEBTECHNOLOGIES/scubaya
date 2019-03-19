<?php

namespace App\Scubaya\Services;

use View;
use Blade;

use Symfony\Component\Debug\Exception\FatalThrowableError;

class ViewService
{
    protected $namespace_depth = [
        'model'         => 'Models',
        'controller'    => 'Controllers',
    ];

    protected $strip_from_class_name = [
        'model'         => 'Model',
        'controller'    => 'Controller',
    ];

    protected $cache = [];

    public static function  render($string, $data)
    {
        $data['__env'] = app(\Illuminate\View\Factory::class);

        $php = Blade::compileString($string);

        $obLevel = ob_get_level();
        ob_start();
        extract($data, EXTR_SKIP);

        try
        {
            eval('?' . '>' . $php);
        }
        catch (\Exception $e)
        {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        }
        catch (\Throwable $e)
        {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new FatalThrowableError($e);
        }

        return ob_get_clean();
    }
}