<?php

/*
 * Fast API Config
 */


return [

    //Paths to check for controllers that use fast-api
    'paths' => [
        app_path('Http/Controllers'),
    ],

    //specify controllers that not included on the paths
    'controllers' => [
        \App\Http\Controllers\CustomController::class
    ],
];
