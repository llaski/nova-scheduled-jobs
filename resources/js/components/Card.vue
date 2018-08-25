<template>
    <card class="h-auto p-4">
        <h2 class="text-90 font-light mb-4">Scheduled Jobs</h2>

        <p v-if="!loading && !jobs.length">You do not currently have any scheduled jobs.</p>

        <loader v-if="loading" class="mb-4"></loader>

        <table v-if="!loading && jobs.length" class="table w-full">
            <thead>
                <tr>
                    <th class="text-left">Command/Job</th>
                    <th class="text-left">Description</th>
                    <th class="text-left">Schedule</th>
                    <th class="text-left">Expression</th>
                    <th class="text-left">Next Run At</th>
                    <th class="text-left">Without Overlapping</th>
                    <th class="text-left">On One Server</th>
                    <th class="text-left">Run In Maintenance Mode</th>
                </tr>
            </thead>
            <tbody>
                <job-row v-for="(job, index) in jobs" :key="index" :job="job"></job-row>
            </tbody>
        </table>

    </card>
</template>

<script>

import JobRow from './JobRow.vue'

export default {

    components: { JobRow },

    props: ['card'],

    data: () => {
        return {
            loading: false,
            jobs: [],
        }
    },

    mounted() {
        this.fetchJobs()
    },

    methods: {

        fetchJobs() {
            this.loading = true

            Nova.request().get('/nova-vendor/nova-scheduled-jobs/jobs').then((response) => {
                this.loading = false
                this.jobs = response.data

                setTimeout(this.fetchJobs, 60 * 1000)
            })
        },

        formatNextRunAt(date) {
            return moment(date).fromNow().replace(/^\w/, c => c.toUpperCase())
        }
    }

}
</script>
