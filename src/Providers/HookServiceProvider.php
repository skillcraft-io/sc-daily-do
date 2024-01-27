<?php

namespace Skillcraft\DailyDo\Providers;

use Botble\Base\Facades\Assets;
use Botble\Base\Supports\ServiceProvider;
use Botble\Dashboard\Events\RenderingDashboardWidgets;
use Botble\Dashboard\Supports\DashboardWidgetInstance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Skillcraft\Core\Models\CoreModel;
use Skillcraft\DailyDo\Actions\SyncDailyDoAction;
use Skillcraft\DailyDo\Supports\DailyDoManager;

class HookServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        (new DailyDoManager())->load();

        $this->app['events']->listen(RenderingDashboardWidgets::class, function () {
            add_filter(DASHBOARD_FILTER_ADMIN_LIST, [$this, 'registerDashboardWidgets'], 21, 2);
        });

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

        Assets::addScriptsDirectly(['/vendor/core/plugins/sc-daily-do/js/daily-do.js']);

        return (new DashboardWidgetInstance())
            ->setPermission('daily-do.index')
            ->setKey('widget_daily_do')
            ->setTitle(trans('plugins/sc-daily-do::daily-do.widget_daily_do'))
            ->setIcon('fas fa-edit')
            ->setColor('yellow')
            ->setRoute(route('daily-do.widget.todo-list'))
            ->setBodyClass('')
            ->setColumn('col-md-6 col-sm-6')
            ->init($widgets, $widgetSettings);
    }
}
