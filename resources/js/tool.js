Nova.booting((Vue, router) => {
    router.addRoutes([
        {
            name: 'NovaScheduledJobs',
            path: '/scheduled-jobs',
            component: require('./components/Tool'),
        },
    ]);
});
