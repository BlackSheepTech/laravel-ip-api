<?php

namespace BlackSheepTech\IpApi\Enums;

use ArchTech\Enums\InvokableCases;
use ArchTech\Enums\Values;

/**
 * @method static string English()
 * @method static string German()
 * @method static string Spanish()
 * @method static string Portuguese()
 * @method static string French()
 * @method static string Japanese()
 * @method static string Chinese()
 * @method static string Russian()
 */
enum ReturnLanguage: string
{
    use InvokableCases;
    use Values;

    case English = 'en';
    case German = 'de';
    case Spanish = 'es';
    case Portuguese = 'pt-BR';
    case French = 'fr';
    case Japanese = 'ja';
    case Chinese = 'zh-CN';
    case Russian = 'ru';
}
