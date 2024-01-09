<?php

namespace Skillcraft\DailyDo\Actions;

use Skillcraft\DailyDo\Models\DailyDo;
use Skillcraft\Core\Models\CoreModel;
use Skillcraft\DailyDo\Supports\DailyDoManager;

class SyncDailyDoAction
{
    public function handle(CoreModel $model, bool $isDelete = false): void
    {
        if (DailyDoManager::isSupported($model)) {
            $get_tasks = $model->getDailyDoTasks();
               
            if (sizeOf($get_tasks) > 0) {
                foreach ($get_tasks as $key => $value) {
                    if ($isDelete) {
                        /**
                         * TODO: Implement better way to delete the correct records.
                         * REF: Micro Farm Plugin
                         * For now, dont delete, better to be explicit in deleting then
                         * possibly deleting the wrong item.
                         */
                        /*
                        (new DailyDo())->query()->where([
                                'module_type' => $value['module']::class,
                                'module_id' => $value['module']->id,
                            ])->delete();
                            */
                    } else {
                        (new DailyDo())->query()->updateOrCreate([
                            'module_type' => $value['module']::class,
                            'module_id' => $value['module']->id,
                        ], [
                            'title' => $value['title'],
                            'description' => $value['description'],
                            'due_date' => now()->toDateString(),
                        ]);
                    }
                }
            }
        }
    }
}
