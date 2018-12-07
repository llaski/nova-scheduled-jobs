<template>
    <div>
        <heading class="mb-4">
            Scheduled Jobs
        </heading>

        <card class="h-auto p-4 mb-4 overflow-scroll">
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
                        <th class="text-left">Dispatch</th>
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
                        <td>
                            <button                                
                                title="Dispatch"
                                class="appearance-none mr-3"
                                :class="canDispatchCommand(job.command) ? 'text-70 hover:text-primary' : 'cursor-default text-40'"
                                :disabled="!canDispatchCommand(job.command)"
                                @click.prevent="openConfirmationModal(job)"
                            >
                                <icon type="play" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <portal to="modals">
                <transition name="fade">                    
                    <dispatch-job-modal
                        v-if="confirmDispatchJobModal"
                        @confirm="confirmDispatchJob"
                        @close="confirmDispatchJobModal = false"
                        :command="dispatchJob.command"
                    />                    
                </transition>
            </portal>
        </card>
    </div>
</template>

<script>
import formatters from '../mixins/formatters'

export default {
    mixins: [formatters],
    
    data: () => ({
        jobs: [],
        loading: false,
        dispatchJob: null,
        confirmDispatchJobModal: false,
    }),

    mounted() {
        this.fetchJobs()
    },

    methods: {        

        canDispatchCommand(command) {
            if(command){
                return command.includes("\Jobs")
            }

            return false;
        },

        openConfirmationModal(job) {
            this.dispatchJob = job
            this.confirmDispatchJobModal = true
        },
        
        confirmDispatchJob() {
            const job = this.dispatchJob
            Nova.request().post('/nova-vendor/llaski/nova-scheduled-jobs/dispatch-job', { command: job.command })
                .then((response) => {                    
                    this.confirmDispatchJobModal = false
                    this.$toasted.show('The job was dispatched!', { type: 'success' })
                }).catch((error) => {
                    this.confirmDispatchJobModal = false
                    this.$toasted.show(error.response.data.message, { type: 'error' })
                })
        },   

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
