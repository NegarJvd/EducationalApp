<?php

namespace App\Http\Controllers;

use App\Components\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, Response;

    public $perPagePanel = 40;

    public $perPageApp = 50;

    public static function perPage($type="App"){
        switch ($type){
            case "Panel":
                return 40;

            case "App":
                return 50;
        }

        return 10;
    }

    public function sms_template($name){
        switch ($name){
            case "otp":
                return 828717;
            case "password":
                return 121873;
        }

        return 65151;
    }
}
