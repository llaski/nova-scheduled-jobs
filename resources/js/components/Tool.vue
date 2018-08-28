<template>
    <div>
        <heading class="mb-4">
            Scheduled Jobs
        </heading>

        <card class="h-auto p-4 mb-4">
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
                    <tr v-for="(job, index) in jobs" :job="job">
                        <td>{{ job.command }}</td>
                        <td class="py-2">{{ job.description }}</td>
                        <td class="py-2">{{ job.humanReadableExpression }}</td>
                        <td>{{ job.expression }}</td>
                        <td>{{ formatNextRunAt(job.nextRunAt) }}</td>
                        <td>{{ job.withoutOverlapping ? 'Yes' : 'No' }}</td>
                        <td>{{ job.onOneServer ? 'Yes' : 'No' }}</td>
                        <td>{{ job.evenInMaintenanceMode ? 'Yes' : 'No' }}</td>
                    </tr>
                </tbody>
            </table>

        </card>

    </div>
</template>

<script>
import formatters from '../mixins/formatters'

export default {
    mixins: [formatters],

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

            Nova.request().get('/nova-vendor/llaski/nova-scheduled-jobs/jobs').then((response) => {
                this.loading = false
                this.jobs = response.data

                setTimeout(this.fetchJobs, 60 * 1000)
            })
        },

    }

}
</script>
