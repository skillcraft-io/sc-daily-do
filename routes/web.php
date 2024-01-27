<?php

use Botble\Base\Facades\BaseHelper;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Skillcraft\DailyDo\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => BaseHelper::getAdminPrefix(), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'daily-dos', 'as' => 'daily-do.'], function () {
            Route::resource('', 'DailyDoController')->parameters(['' => 'daily-do']);

            Route::get('widgets/todo-list', [
                'as' => 'widget.todo-list',
                'uses' => 'DailyDoController@getWidgetDailyTodo',
                'permission' => 'daily-do.index',
            ]);

            Route::get('complete/todo-list', [
                'as' => 'complete',
                'uses' => 'DailyDoController@processCompletingDailyDo',
                'permission' => 'daily-do.index',
            ]);
        });
    });
});
