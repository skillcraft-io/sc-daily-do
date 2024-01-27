class DailyDos {
    init() {
        this.handleDailyDo()
    }

    handleDailyDo() {

        $(document).on('click', '#myDailyDo', (e) => {
            const params = {}
            let current = $(e.currentTarget)
            params['task_id'] = current.data('dailytask')
            updateDailyDos(params)
        });

        let updateDailyDos = (params) => {
            $httpClient
                .make()
                .get(route('daily-do.complete'), { data: params })
                .then(() => {
                    BDashboard.loadWidget($('#widget_daily_do').find('.widget-content'), route('daily-do.widget.todo-list'))
               })
        }
    }
}

$(() => {
    new DailyDos().init()
    BDashboard.loadWidget($('#widget_daily_do').find('.widget-content'), route('daily-do.widget.todo-list'))
})
