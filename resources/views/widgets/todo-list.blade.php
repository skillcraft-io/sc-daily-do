@if ($todos->isNotEmpty())
    <div class="table-responsive">
        <x-core::table>
            <x-core::table.header>
                <x-core::table.header.cell>
                </x-core::table.header.cell>
                <x-core::table.header.cell>
                    {{ trans('core/base::tables.title') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell class="text-end">
                    {{ trans('core/base::tables.description') }}
                </x-core::table.header.cell>
                <x-core::table.header.cell class="text-end">
                    {{ trans('plugins/daily-do::daily-do.tables.due_date') }}
                </x-core::table.header.cell>
            </x-core::table.header>

            <x-core::table.body>
                
                @foreach ($todos as $todo)
                    <x-core::table.body.row>
                        <x-core::table.body.cell >
                            <div class="btn btn-success btn-sm " title="{{ trans('plugins/daily-do::daily-do.mark_as_complete') }}" >
                                <i class="fas fa-check" id="myDailyDo" data-dailytask="{{ $todo->id }}"></i>
                            </div>
                        </x-core::table.body.cell>
                        <x-core::table.body.cell>
                               <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#daily-do-{{ $todo->id }}-modal" > <strong>{{ Str::limit($todo->title, 80) }}</strong></a>
                        </x-core::table.body.cell>
                        <x-core::table.body.cell>
                                <strong>{!! BaseHelper::clean(Str::limit($todo->description, 75)) !!}</strong>
                        </x-core::table.body.cell>
                        <x-core::table.body.cell class="text-end text-nowrap">
                            {{ BaseHelper::formatDate($todo->due_date) }}
                        </x-core::table.body.cell>
                    </x-core::table.body.row>

        <x-core::modal
            id="daily-do-{{ $todo->id }}-modal"
            :title="__('Daily Do: :name', ['name' => $todo->title])"
            size="lg"
            class="daily-do-modal"
        >
            <div class="row">
                <div class="col-md-12">
                    
                    {!! BaseHelper::clean($todo->description) !!}
                </div>
            </div>
            <x-slot:footer>
                <div class="btn btn-success" title="{{ trans('plugins/daily-do::daily-do.mark_as_complete') }}" data-bs-dismiss="modal">
                        <i class="fas fa-check" id="myDailyDo" data-dailytask="{{ $todo->id }}"></i>
                </div>
            </x-slot:footer>
        </x-core::modal>

                @endforeach
            </x-core::table.body>
        </x-core::table>
    </div>
@else
    <x-core::empty-state
        :title="trans('plugins/daily-do::daily-do.no_new_tasks_title')"
        :subtitle="trans('plugins/daily-do::daily-do.no_new_tasks_now')"
    />
@endif
