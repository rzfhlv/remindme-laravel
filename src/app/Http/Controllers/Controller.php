<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    const ERR_NOT_FOUND = 'ERR_NOT_FOUND';
    const ERR_INTERNAL_SERVER = 'ERR_INTERNAL_SERVER';
    const ERR_INVALID_REQUEST = 'ERR_INVALID_REQ';
    const MSG_NOT_FOUND = 'data not found';
    const MSG_INTERNAL_SERVER = 'internal server error';
}
