<template>
    <card class="h-auto p-4">
        <h2 class="text-90 font-light mb-4">Scheduled Jobs</h2>

        <p v-if="!loading && !jobs.length">You do not currently have any scheduled jobs.</p>

        <loader v-if="loading" class="mb-4"></loader>

        <table v-if="!loading && jobs.length" class="table w-full">
            <thead>
                <tr>
                    <th class="text-left">Command/Job</th>
                    <th class="text-left">Expression</th>
                    <th class="text-left">Next Run At</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(job, index) in jobs" :job="job">
                    <td>{{ job.command }}</td>
                    <td>{{ job.expression }}</td>
                    <td>{{ formatNextRunAt(job.nextRunAt) }}</td>
                </tr>
            </tbody>
        </table>

    </card>
</template>

<script>
import formatters from '../mixins/formatters'

export default {

    mixins: [formatters],

    props: ['card'],

    data: () => {
        return {
            loading: false,
            jobs: [],
        }
    },

    mounted() {
        console.log('got here')
        this.fetchJobs()
    },

    methods: {

        fetchJobs() {
            this.loading = true

            Nova.request().get('/nova-vendor/llaski/nova-scheduled-jobs/jobs').then((response) => {
                this.loading = false
                this.jobs = response.data

                setTimeout(this.fetchJobs, 60 * 1000)
            })
        },

    }

}
</script>
