<?php

namespace Skillcraft\DailyDo\Providers;

use Botble\Base\Facades\Assets;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Botble\Base\Supports\ServiceProvider;
use Skillcraft\DailyDo\Actions\SyncDailyDoAction;
use Skillcraft\DailyDo\Supports\DailyDoManager;
use Botble\Dashboard\Events\RenderingDashboardWidgets;
use Botble\Dashboard\Supports\DashboardWidgetInstance;
use Skillcraft\Core\Models\CoreModel;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app['events']->listen(RenderingDashboardWidgets::class, function () {
            add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 21, 2);
        });

        (new DailyDoManager)->load();


        add_action(ACTION_HOOK_SKILLCRAFT_CORE_MODEL_AFTER_CREATED, function (CoreModel $model) {
            (new SyncDailyDoAction())->handle($model);
        }, 1, 1);

        add_action(ACTION_HOOK_SKILLCRAFT_CORE_MODEL_AFTER_UPDATED, function (CoreModel $model) {
            (new SyncDailyDoAction())->handle($model);
        }, 1, 1);
    }

    public function registerDashboardWidgets(array $widgets, Collection $widgetSettings): array
    {
        if (! Auth::guard()->user()->hasPermission('daily-do.index')) {
            return $widgets;
        }

        Assets::addScriptsDirectly(['/vendor/core/plugins/daily-do/js/daily-do.js']);

        return (new DashboardWidgetInstance())
            ->setPermission('daily-do.index')
            ->setKey('widget_daily_do')
            ->setTitle(trans('plugins/daily-do::daily-do.widget_daily_do'))
            ->setIcon('fas fa-edit')
            ->setColor('yellow')
            ->setRoute(route('daily-do.widget.todo-list'))
            ->setBodyClass('')
            ->setColumn('col-md-6 col-sm-6')
            ->init($widgets, $widgetSettings);
    }
}
