<?php

namespace Skillcraft\DailyDo\Tables;

use Botble\Base\Facades\BaseHelper;
use Botble\Base\Facades\Html;
use Skillcraft\DailyDo\Models\DailyDo;
use Botble\Table\Abstracts\TableAbstract;
use Botble\Table\Actions\DeleteAction;
use Botble\Table\Actions\EditAction;
use Botble\Table\BulkActions\DeleteBulkAction;
use Botble\Table\Columns\Column;
use Botble\Table\Columns\DateColumn;
use Botble\Table\Columns\YesNoColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\JsonResponse;

class DailyDoTable extends TableAbstract
{
    public function setup(): void
    {
        $this
            ->model(DailyDo::class)
            ->addActions([
                EditAction::make()
                    ->route('daily-do.edit'),
                DeleteAction::make()
                    ->route('daily-do.destroy'),
            ]);
    }

    public function ajax(): JsonResponse
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('name', function (DailyDo $item) {
                if (! $this->hasPermission('daily-do.edit')) {
                    return BaseHelper::clean($item->name);
                }
                return Html::link(route('daily-do.edit', $item->getKey()), BaseHelper::clean($item->name));
            });

        return $this->toJson($data);
    }

    public function query(): Relation|Builder|QueryBuilder
    {
        $query = $this
            ->getModel()
            ->query()
            ->select([
               'id',
               'title',
               'description',
               'due_date',
               'is_completed',
           ]);

        return $this->applyScopes($query);
    }

    public function columns(): array
    {
        return [
            Column::make('title'),
            Column::make('description'),
            DateColumn::make('due_date'),
            YesNoColumn::make('is_completed'),
        ];
    }

    public function buttons(): array
    {
        return $this->addCreateButton(route('daily-do.create'), 'daily-do.create');
    }

    public function bulkActions(): array
    {
        return [
            DeleteBulkAction::make()->permission('daily-do.destroy'),
        ];
    }

    public function getBulkChanges(): array
    {
        return [
            'name' => [
                'title' => trans('core/base::tables.title'),
                'type' => 'text',
                'validate' => 'required|max:100',
            ],
            'is_completed' => [
                'title' => trans('plugins/sc-daily-do::daily-do.forms.is_completed'),
                'type' => 'select',
                'choices' => [
                    1 => trans('core/base::base.yes'),
                    0 => trans('core/base::base.no'),
                ],
                'validate' => 'required|boolean',
            ],
            'due_date' => [
                'title' => trans('plugins/sc-daily-do::daily-do.forms.due_date'),
                'type' => 'date',
            ],
        ];
    }

    public function getFilters(): array
    {
        return $this->getBulkChanges();
    }
}
