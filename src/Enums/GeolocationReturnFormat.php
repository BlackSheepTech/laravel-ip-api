<?php

namespace BlackSheepTech\IpApi\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

/**
 * @method static string JSON()
 * @method static string XML()
 * @method static string CSV()
 * @method static string Newline()
 * @method static string PHP()
 */
enum GeolocationReturnFormat: string
{
    use InvokableCases;
    use Values;

    case JSON = 'json';
    case XML = 'xml';
    case CSV = 'csv';
    case Newline = 'line';
    case PHP = 'php';
}
