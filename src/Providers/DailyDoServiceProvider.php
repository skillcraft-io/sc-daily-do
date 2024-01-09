<?php

namespace Skillcraft\DailyDo\Providers;

use Botble\Base\Facades\DashboardMenu;
use Botble\Base\Supports\ServiceProvider;
use Botble\Base\Traits\LoadAndPublishDataTrait;

class DailyDoServiceProvider extends ServiceProvider
{
    use LoadAndPublishDataTrait;

    public function boot(): void
    {
        $this->app->register(CommandServiceProvider::class);

        $this->app->register(EventServiceProvider::class);

        $this
            ->setNamespace('plugins/daily-do')
            ->loadHelpers()
            ->loadAndPublishConfigurations(['permissions', 'general'])
            ->loadMigrations()
            ->loadAndPublishTranslations()
            ->loadAndPublishViews()
            ->loadRoutes();

        DashboardMenu::default()->beforeRetrieving(function () {
            DashboardMenu::make()
            ->registerItem([
                'id' => 'cms-plugins-daily-do',
                'priority' => 4,
                'parent_id' => null,
                'name' => 'plugins/daily-do::daily-do.name',
                'icon' => 'ti ti-checklist',
                'url' => route('daily-do.index'),
                'permissions' => ['daily-do.index'],
            ]);
        });

        $this->app->booted(function () {
            $this->app->register(HookServiceProvider::class);
        });
    }
}
