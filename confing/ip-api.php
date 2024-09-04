<?php

return [

    /*-------------------------------------------------------------------------
    | DiceBear Base URL
    |--------------------------------------------------------------------------
    |
    | This value is the base URL used by the package to generate the requests
    | to the DiceBear API.
    |
    */

    'base_url' => env('IP_API_BASE_URL', 'http://ip-api.com/'),

    /*-------------------------------------------------------------------------
    | API Key
    |------------------------------------------------------------------------*/

    'api_key' => env('IP_API_KEY'),

    /*-------------------------------------------------------------------------
    | Default Parameters
    |--------------------------------------------------------------------------
    |
    | Default parameters to be used during the requests to the IpApi Service.
    |
    */

    'default' => [
        'query' => env('IP_API_DEFAULT_QUERY'),
        'language' => env('IP_API_DEFAULT_LANG', 'en'),
        'format' => env('IP_API_DEFAULT_FORMAT', 'json'),
        'fields' => env('IP_API_DEFAULT_FIELDS', 'status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query'),
    ],
];
