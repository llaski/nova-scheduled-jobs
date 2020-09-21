Nova.booting((Vue, router) => {
    Vue.component('nova-scheduled-jobs', require('./components/Card'))
    Vue.component('nova-scheduled-jobs-manage', require('./components/CardManage'))
    Vue.component('dispatch-job-modal', require('./components/DispatchJobModal'))

    router.addRoutes([{
        name: 'NovaScheduledJobs',
        path: '/scheduled-jobs',
        component: require('./components/Tool'),
    }, ])
})
