<p align="center">
    <a href="https://github.com/BlackSheepTech/laravel-ip-api" target="_blank">
        <img src="https://avatars.githubusercontent.com/u/85756821?s=400&u=14843f72938dc40cbd14400f5b3daad45f054f43&v=4" width="200" alt="BlackSheepTech UiAvatars">
    </a>
</p>

<p align="center">
    <a href="https://packagist.org/packages/black-sheep-tech/laravel-ip-api"><img src="https://img.shields.io/packagist/v/black-sheep-tech/laravel-ip-api" alt="Current Version"></a>
    <a href="https://packagist.org/packages/black-sheep-tech/laravel-ip-api"><img src="https://img.shields.io/packagist/dt/black-sheep-tech/laravel-ip-api" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/black-sheep-tech/laravel-ip-api"><img src="https://img.shields.io/github/license/BlackSheepTech/laravel-ip-api" alt="License"></a>
    <a href="https://packagist.org/packages/black-sheep-tech/laravel-ip-api"><img src="https://img.shields.io/github/stars/BlackSheepTech/laravel-ip-api" alt="Stars"></a>
</p>

Laravel IpApi is a Laravel focused package that provides an easy way to get information about an IP address using the [IpApi](https://ip-api.com/) API.

## Installation

You can install the package via composer:

```bash
composer require black-sheep-tech/laravel-ip-api
```

## General Config

The package just works out of the box, but you can customize it to your liking.

### On the fly

```php
// Set API Key fluently
$info = IpApi::geolocation()->apiKey('yourapikeyhere')->query('google.com')->get();
// Set Base URL fluently
$info = IpApi::geolocation()->baseUrl('http://ip-api.com/')->query('google.com')->get();
```

### Using environment variables

You can set the following environment variables in your `.env` file:

```dotenv
IP_API_BASE_URL=http://ip-api.com/
IP_API_API_KEY=your_api_key
IP_API_DEFAULT_QUERY=google.com
IP_API_DEFAULT_LANG=en
IP_API_DEFAULT_FORMAT=json
IP_API_DEFAULT_FIELDS=country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query
```

### Config File

For a more tailored configuration, you can publish the config file to your project by running the following command:

```bash
php artisan vendor:publish --provider="BlackSheepTech\IpApi\IpApiServiceProvider"
```

This will create a `ip-api.php` file in your `config` directory, where you can customize the package config to your liking.

#### Overusage Protection

The package comes with a built-in overusage protection feature that will prevent you from making excessive requests to the API getting you temporarily banned. But, of course, you can disable this feature by setting the "IP_API_OVERUSAGE_PROTECTION" environment variable to false.

```dotenv
IP_API_OVERUSAGE_PROTECTION=false
```

It can also be disabled on the fly:

```php
$info = IpApi::geolocation()->disableOverusageProtection()->query('google.com')->get();
```

## Usage

The package offers acess to both the geolocation and Batch APIs.

### Geolocation API

- Basic Usage

```php
use BlackSheepTech\IpApi\IpApi;

$info = IpApi::geolocation()->query('google.com')->get();
```

- Advanced Usage

```php
//Return Format - can be json, xml, csv, line or php
$info = IpApi::geolocation()->query('google.com')->format('json')->get();

//Return Fields - Supported fields can be found at https://ip-api.com/docs/api:json
$info = IpApi::geolocation()->query('google.com')->fields('countryCode,lat,lon,timezone,query')->get();
// or
$info = IpApi::geolocation()->query('google.com')->fields(['countryCode', 'lat', 'lon', 'timezone', 'query'])->get();

//Return Language - Supported languages can be found at https://ip-api.com/docs/api:json
$info = IpApi::geolocation()->query('google.com')->language('es')->get();
```

- Return as Object

You can get the response as an object by doing the following:

```php
$info = IpApi::geolocation()->query('google.com')->get(true);
//Or
$info = IpApi::geolocation()->query('google.com')->getAsObject();
//When using object return, the format provided is disregarded.
$info = IpApi::geolocation()->query('google.com')->format('php')->getAsObject(); //->format('php') will be ignored and have no impact on the response.
```

### Batch API

- Basic Usage

```php
use BlackSheepTech\IpApi\IpApi;

$entities = [
    {
        "query": "google.com"
    },{
        "query": "facebook.com"
    }
];

$info = IpApi::batch()->entities($entities)->get();
```

- Customized Return

```php
use BlackSheepTech\IpApi\IpApi;

$entities = [
    {
        "query": "google.com",
        "fields": "country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query",
        "lang": "en",
    },{
        "query": "facebook.com",
        "fields": "country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query",
        "lang": "en",
    }
];

$info = IpApi::batch()->entities($entities)->get();
```

- Return as Object

You can get the response as an object by doing the following:

```php

$entities = [
    {
        "query": "google.com"
    },{
        "query": "facebook.com"
    }
];

$info = IpApi::batch()->entities($entities)->get(true);
//Or
$info = IpApi::batch()->entities($entities)->getAsObject();
```

## Requirements

- PHP 8.0 or higher
- Laravel framework version 9.0 or higher

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request on GitHub.

## Credits

- [Israel Pinheiro](https://github.com/IsraelPinheiro)
- [All Contributors](https://github.com/BlackSheepTech/laravel-ip-api/graphs/contributors)