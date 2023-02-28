<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class HelpersAPIController extends BaseController {

    function app_versions(Request $request) {
        $response = [
            'ios'        => '2.1.4',
            'android'    => '2.0.2',
        ];
        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }
}
