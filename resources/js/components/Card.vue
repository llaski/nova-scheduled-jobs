<template>
    <Card class="h-auto p-4">
        <Heading class="mb-6">{{ __('Scheduled Jobs') }}</Heading>

        <p v-if="!loading && !jobs.length">
            {{ __('You do not currently have any scheduled jobs.') }}
        </p>

        <loader v-if="loading" class="mb-4"></loader>

        <table v-if="!loading && jobs.length" class="table table-fixed w-full">
            <thead>
                <tr>
                    <th
                        class="w-3/5 text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                    >
                        <span>{{ __('Name') }}</span>
                    </th>
                    <th
                        class="w-1/5 text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                    >
                        <span>{{ __('Expression') }}</span>
                    </th>
                    <th
                        class="w-1/5 text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                    >
                        <span>{{ __('Next Due') }}</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(job, index) in jobs" :job="job">
                    <td class="w-3/5 px-2 py-2 whitespace-nowrap dark:bg-gray-800">
                        <p class="text-sm font-bold truncate">
                            {{ job.command }}
                        </p>
                    </td>
                    <td class="w-1/5 text-sm px-2 py-2 dark:bg-gray-800">
                        {{ job.expression }}
                    </td>
                    <td class="w-1/5 px-2 py-2 dark:bg-gray-800">
                        <p class="text-sm">
                            {{ job.nextDueHumanReadable }}
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </Card>
</template>

<script>
export default {
    props: ['card'],

    data: () => {
        return {
            jobs: [],
            loading: false,
        }
    },

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
    },
}
</script>

<style>
.table-fixed {
    table-layout: fixed;
}

.w-1\/5 {
    width: 20%;
}
.w-2\/5 {
    width: 40%;
}
.w-3\/5 {
    width: 60%;
}
</style>
