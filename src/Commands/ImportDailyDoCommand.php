<?php

namespace Skillcraft\DailyDo\Commands;

use Illuminate\Console\Command;
use Skillcraft\DailyDo\Supports\DailyDoManager;
use Skillcraft\DailyDo\Actions\SyncDailyDoAction;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand('skillcraft:daily-do:import', 'Imported supported hook daily dos command')]
class ImportDailyDoCommand extends Command
{

    public function handle(): int
    {
        $supported = DailyDoManager::getSupportedHooks();

        foreach ($supported as $key => $value) {
            if (method_exists($key, 'getDailyDoTasks')) {
                $get_tasks = (new $key())->getDailyDoTasks();

               
                if (sizeOf($get_tasks) > 0) {
                    foreach ($get_tasks as $key => $value) {
                        (new SyncDailyDoAction)->handle($value['module']);
                    }
                }
            }
        }

        return self::SUCCESS;
    }
}
