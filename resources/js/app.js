Nova.booting((Vue, router) => {
    Vue.component('nova-scheduled-jobs', require('./components/Card'))
    router.addRoutes([{
        name: 'NovaScheduledJobs',
        path: '/scheduled-jobs',
        component: require('./components/Tool'),
    }, ])
})
