<?php

namespace BlackSheepTech\IpApi\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

/**
 * @method static string CONNECT()
 * @method static string DELETE()
 * @method static string GET()
 * @method static string HEAD()
 * @method static string OPTIONS()
 * @method static string PATCH()
 * @method static string POST()
 * @method static string PUT()
 * @method static string TRACE()
 */
enum HttpMethod: string
{
    use InvokableCases;
    use Values;

    case CONNECT = 'CONNECT';
    case DELETE = 'DELETE';
    case GET = 'GET';
    case HEAD = 'HEAD';
    case OPTIONS = 'OPTIONS';
    case PATCH = 'PATCH';
    case POST = 'POST';
    case PUT = 'PUT';
    case TRACE = 'TRACE';
}
