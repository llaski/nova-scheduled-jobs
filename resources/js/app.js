import DispatchJobModal from './components/DispatchJobModal'
import Card from './components/Card'
import Tool from './pages/Tool'

Nova.booting((app, store) => {
    app.component('NovaScheduledJobsCard', Card)
    app.component('DispatchJobModal', DispatchJobModal)

    Nova.inertia('NovaScheduledJobs', Tool)
})
