import Card from './components/Card'
import DispatchJobModal from './components/DispatchJobModal'
import Tool from './components/Tool'

Nova.booting((app, router) => {
    app.component('nova-scheduled-jobs', Card)
    app.component('dispatch-job-modal', DispatchJobModal)

    Nova.inertia('NovaScheduledJobs', Tool)
})