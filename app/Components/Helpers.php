<?php

if (!function_exists('cast_to_number')) {
    function cast_to_number($input) {
        if(is_float($input) || is_int($input)) {
            return $input;
        }
        if(!is_string($input)) {
            return false;
        }
        if(preg_match('/^-?\d+$/', $input)) {
            return intval($input);
        }
        if(preg_match('/^-?\d+\.\d+$/', $input)) {
            return floatval($input);
        }
        return false;
    }
}

if (!function_exists('timestamp_to_date')) {
    function timestamp_to_date($timestamp, $format = "Y-m-d H:i:s")
    {
        $timestamp = substr($timestamp, 0, -3);
        $int_alt_Field = cast_to_number($timestamp);
        return date($format, $int_alt_Field);
    }
}

if (!function_exists('create_basic_dir')) {
    function create_basic_dir() {
        $storage_files = public_path('/storage_files');
        if (!is_dir($storage_files)) {
            mkdir($storage_files, 0777);
        }

        $avatars_files = $storage_files . '/avatars';
        if (!is_dir($avatars_files)) {
            mkdir($avatars_files, 0777);
        }

        $products_files = $storage_files . '/products';
        if (!is_dir($products_files)) {
            mkdir($products_files, 0777);
        }

        return [
            'storage_files' => [
                'public_path' => $storage_files,
                'path' => '/storage_files',
            ],
            'avatars_files' => [
                'public_path' => $avatars_files,
                'path' => '/storage_files/avatars',
            ],
            'products_files' => [
                'public_path' => $products_files,
                'path' => '/storage_files/products',
            ],
        ];
    }
}
