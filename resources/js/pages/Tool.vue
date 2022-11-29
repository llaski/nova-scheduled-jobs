<template>
    <div>
        <Head title="Nova Scheduled Jobs" />

        <Heading class="mb-6">{{ __('Scheduled Jobs') }}</Heading>

        <Card class="p-2 mb-4 overflow-scroll">
            <p v-if="!loading && !jobs.length" class="p-4 text-lg">
                {{ __('You do not currently have any scheduled jobs.') }}
            </p>

            <Loader v-if="loading" class="py-4"></Loader>

            <table
                v-if="!loading && jobs.length"
                class="w-full divide-y divide-gray-100 dark:divide-gray-700"
            >
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th
                            class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span>{{ __('Name/Description') }}</span>
                        </th>
                        <th
                            class="text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span class="w-40 inline-block">{{ __('Schedule') }}</span>
                        </th>
                        <th
                            class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span>{{ __('Expression') }}</span>
                        </th>
                        <th
                            class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span>{{ __('Timezone') }}</span>
                        </th>
                        <th
                            class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span>{{ __('Next Due') }}</span>
                        </th>
                        <th
                            class="text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span>{{ __('Without Overlapping') }}</span>
                        </th>
                        <th
                            class="text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span>{{ __('On One Server') }}</span>
                        </th>
                        <th
                            class="text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span>{{ __('Run In Background') }}</span>
                        </th>
                        <th
                            class="text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span>{{ __('Run In Maintenance Mode') }}</span>
                        </th>
                        <th
                            class="text-left px-2 whitespace-nowrap uppercase text-gray-500 text-xxs tracking-wide py-2"
                        >
                            <span>{{ __('Dispatch') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    <tr v-for="(job, index) in jobs" :job="job">
                        <td class="px-2 py-2 whitespace-nowrap dark:bg-gray-800">
                            <p class="mb-1 font-bold">{{ job.command }}</p>
                            <p class="text-sm">{{ job.description }}</p>
                        </td>
                        <td class="px-2 py-2 dark:bg-gray-800">
                            <span class="w-40 inline-block">{{ job.expressionHumanReadable }}</span>
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap dark:bg-gray-800">
                            {{ job.expression }}
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap dark:bg-gray-800">
                            {{ job.timezone }}
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap dark:bg-gray-800">
                            <p class="mb-1 font-bold">{{ job.nextDue }}</p>
                            <p class="text-sm">
                                {{ job.nextDueHumanReadable }}
                            </p>
                        </td>
                        <td class="px-2 py-2 dark:bg-gray-800">
                            {{ job.withoutOverlapping ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-2 py-2 dark:bg-gray-800">
                            {{ job.onOneServer ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-2 py-2 dark:bg-gray-800">
                            {{ job.runInBackground ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-2 py-2 dark:bg-gray-800">
                            {{ job.evenInMaintenanceMode ? 'Yes' : 'No' }}
                        </td>
                        <td class="px-2 py-2 whitespace-nowrap dark:bg-gray-800">
                            <button
                                title="Dispatch"
                                class="toolbar-button hover:text-primary-500 px-2 disabled:opacity-50 disabled:pointer-events-none"
                                v-tooltip.click="__('Dispatch')"
                                :aria-label="__('Dispatch')"
                                :disabled="!canDispatchCommand(job.command)"
                                @click.stop="openConfirmationModal(job)"
                            >
                                <Icon type="play" />
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <DispatchJobModal
                :show="confirmDispatchJobModal"
                :command="dispatchJob?.command"
                @confirm="confirmDispatchJob"
                @close="confirmDispatchJobModal = false"
            />
        </Card>
    </div>
</template>

<script>
export default {
    data: () => ({
        jobs: [],
        loading: true,
        dispatchJob: null,
        confirmDispatchJobModal: false,
    }),

    mounted() {
        this.fetchJobs()
    },

    methods: {
        fetchJobs() {
            this.loading = true

            Nova.request()
                .get('/nova-vendor/nova-scheduled-jobs/jobs')
                .then((response) => {
                    this.loading = false
                    this.jobs = response.data.data

                    setTimeout(this.fetchJobs, 60 * 1000)
                })
        },

        canDispatchCommand(command) {
            if (command) {
                return command.includes('\Jobs')
            }

            return false
        },

        openConfirmationModal(job) {
            this.dispatchJob = job
            this.confirmDispatchJobModal = true
        },

        confirmDispatchJob() {
            const job = this.dispatchJob
            Nova.request()
                .post('/nova-vendor/nova-scheduled-jobs/dispatch', {
                    command: job.command,
                })
                .then((response) => {
                    this.confirmDispatchJobModal = false
                    Nova.success(this.__('The job was dispatched!'))
                })
                .catch((error) => {
                    this.confirmDispatchJobModal = false
                    if (error.response.status === 422) {
                        Nova.error(this.__(error.response.data.message))
                    }
                    // For 500 responses, Nova request will automatically show toast notification for us in UI
                })
        },
    },
}
</script>

<style>
.w-40 {
    width: 10rem;
}
</style>
