<?php

namespace Skillcraft\DailyDo\Http\Controllers;

use Skillcraft\DailyDo\Http\Requests\DailyDoRequest;
use Skillcraft\DailyDo\Models\DailyDo;
use Botble\Base\Facades\PageTitle;
use Botble\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Skillcraft\DailyDo\Tables\DailyDoTable;
use Botble\Base\Events\CreatedContentEvent;
use Botble\Base\Events\DeletedContentEvent;
use Botble\Base\Events\UpdatedContentEvent;
use Botble\Base\Http\Responses\BaseHttpResponse;
use Skillcraft\DailyDo\Forms\DailyDoForm;
use Botble\Base\Forms\FormBuilder;

class DailyDoController extends BaseController
{
    public function index(DailyDoTable $table)
    {
        PageTitle::setTitle(trans('plugins/sc-daily-do::daily-do.name'));

        return $table->renderTable();
    }

    public function create(FormBuilder $formBuilder)
    {
        PageTitle::setTitle(trans('plugins/sc-daily-do::daily-do.create'));

        return $formBuilder->create(DailyDoForm::class)->renderForm();
    }

    public function store(DailyDoRequest $request, BaseHttpResponse $response)
    {
        $dailyDo = DailyDo::query()->create($request->input());

        event(new CreatedContentEvent(DAILY_DO_MODULE_SCREEN_NAME, $request, $dailyDo));

        return $response
            ->setPreviousUrl(route('daily-do.index'))
            ->setNextUrl(route('daily-do.edit', $dailyDo->getKey()))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    public function edit(DailyDo $dailyDo, FormBuilder $formBuilder)
    {
        PageTitle::setTitle(trans('core/base::forms.edit_item', ['name' => $dailyDo->title]));

        return $formBuilder->create(DailyDoForm::class, ['model' => $dailyDo])->renderForm();
    }

    public function update(DailyDo $dailyDo, DailyDoRequest $request, BaseHttpResponse $response)
    {
        $dailyDo->fill($request->input());

        $dailyDo->save();

        event(new UpdatedContentEvent(DAILY_DO_MODULE_SCREEN_NAME, $request, $dailyDo));

        return $response
            ->setPreviousUrl(route('daily-do.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function destroy(DailyDo $dailyDo, Request $request, BaseHttpResponse $response)
    {
        try {
            $dailyDo->delete();

            event(new DeletedContentEvent(DAILY_DO_MODULE_SCREEN_NAME, $request, $dailyDo));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    public function processCompletingDailyDo(Request $request, BaseHttpResponse $response)
    {

        $dailyDo = (new DailyDo)
            ->query()
            ->where('id', (int) $request->input('data.task_id', 0))
            ->first();


        if ($dailyDo) {
            $dailyDo->is_completed = 1;
            $dailyDo->save();
        }

        return $response
            ->setPreviousUrl(route('daily-do.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    public function getWidgetDailyTodo(Request $request): BaseHttpResponse
    {
        $limit = $request->integer('paginate', 10);
        $limit = $limit > 0 ? $limit : 10;

        $todos = (new DailyDo)->query()
            ->where('is_completed', false)
            ->where('due_date', '=', date('Y-m-d'))
            ->orderByDesc('due_date')
            ->limit($limit)
            ->select(['id', 'title', 'description', 'due_date'])
            ->get();

        return $this
            ->httpResponse()
            ->setData(view('plugins/sc-daily-do::widgets.todo-list', compact('todos', 'limit'))->render());
    }
}
