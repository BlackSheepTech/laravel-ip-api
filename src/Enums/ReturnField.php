<?php

namespace BlackSheepTech\IpApi\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

/**
 * @method static string Status()
 * @method static string Message()
 * @method static string Continent()
 * @method static string ContinentCode()
 * @method static string Country()
 * @method static string CountryCode()
 * @method static string Region()
 * @method static string RegionName()
 * @method static string City()
 * @method static string District()
 * @method static string Zip()
 * @method static string Lat()
 * @method static string Lon()
 * @method static string Timezone()
 * @method static string Offset()
 * @method static string Currency()
 * @method static string Isp()
 * @method static string Org()
 * @method static string As()
 * @method static string AsName()
 * @method static string Reverse()
 * @method static string Mobile()
 * @method static string Proxy()
 * @method static string Hosting()
 * @method static string Query()
 */
enum ReturnField: string
{
    use InvokableCases;
    use Values;

    case Status = 'status';
    case Message = 'message';
    case Continent = 'continent';
    case ContinentCode = 'continentCode';
    case Country = 'country';
    case CountryCode = 'countryCode';
    case Region = 'region';
    case RegionName = 'regionName';
    case City = 'city';
    case District = 'district';
    case Zip = 'zip';
    case Lat = 'lat';
    case Lon = 'lon';
    case Timezone = 'timezone';
    case Offset = 'offset';
    case Currency = 'currency';
    case Isp = 'isp';
    case Org = 'org';
    case As = 'as';
    case AsName = 'asname';
    case Reverse = 'reverse';
    case Mobile = 'mobile';
    case Proxy = 'proxy';
    case Hosting = 'hosting';
    case Query = 'query';
}
