<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Config;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    //$url = Config::get('constants.ip_address').':'.Config::get('constants.port
    //$url = 'http://127.0.0.1:8000/*';

    protected $except = [
        'http://127.0.0.1:8000/*',
    ];
}
