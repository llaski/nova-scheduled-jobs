import jobsFromResponse from '../util/jobsFromResponse'

export default {
    methods: {
        fetchJobs() {
            this.loading = true

            Nova.request()
                .get('/nova-vendor/nova-scheduled-jobs/jobs')
                .then((response) => {
                    this.loading = false
                    this.jobs = jobsFromResponse(response.data)

                    setTimeout(this.fetchJobs, 60 * 1000)
                })
        },
    },
}
