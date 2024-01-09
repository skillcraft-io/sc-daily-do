class DailyDos {
    init() {
        this.handleDailyDo()
    }

    handleDailyDo() {

        $(document).on('click', '#myDailyDo', (e) => {
            const params = {}
            let current = $(e.currentTarget)
            var dailyTaskId = current.data('dailytask');
            params['task_id'] = dailyTaskId
            updateDailyDos(params)
        });

        let updateDailyDos = (params) => {
            $httpClient
                .make()
                .get(route('daily-do.complete'), { data: params })
                .then(({ data }) => {
                    BDashboard.loadWidget($('#widget_daily_do').find('.widget-content'), route('daily-do.widget.todo-list'))
               })
        }
    }
}

$(() => {
    new DailyDos().init()
    BDashboard.loadWidget($('#widget_daily_do').find('.widget-content'), route('daily-do.widget.todo-list'))
})
