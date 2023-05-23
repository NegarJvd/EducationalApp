<?php

namespace App\Http\Controllers;

use App\Components\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, Response;

    public $perPagePanel = 15;

    public $perPageApp = 30;

    public static function perPage($type="App"){
        switch ($type){
            case "Panel":
                return 15;

            case "App":
                return 30;
        }

        return 10;
    }
}
