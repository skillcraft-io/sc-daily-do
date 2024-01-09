<?php

namespace Skillcraft\DailyDo\Supports;

use Skillcraft\Core\Abstracts\HookRegistrarAbstract;

class DailyDoManager extends HookRegistrarAbstract
{
    public static function getScreenName(): string
    {
        return DAILY_DO_MODULE_SCREEN_NAME;
    }

    public static function getModuleName(): string
    {
        return 'daily-do';
    }
}
