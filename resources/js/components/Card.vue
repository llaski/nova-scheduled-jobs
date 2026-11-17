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
                        v-if="showsColumn('command')"
                        class="w-3/5 text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                    >
                        <span>{{ __('Name') }}</span>
                    </th>
                    <th
                        v-if="showsColumn('expression')"
                        class="w-1/5 text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                    >
                        <span>{{ __('Expression') }}</span>
                    </th>
                    <th
                        v-if="showsColumn('nextDue')"
                        class="w-1/5 text-left px-2 uppercase text-gray-500 text-xxs tracking-wide py-2"
                    >
                        <span>{{ __('Next Due') }}</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(job, index) in jobs" :key="index" :job="job">
                    <td
                        v-if="showsColumn('command')"
                        class="w-3/5 px-2 py-2 whitespace-nowrap dark:bg-gray-800"
                    >
                        <p class="text-sm font-bold truncate">
                            {{ job.command }}
                        </p>
                    </td>
                    <td v-if="showsColumn('expression')" class="w-1/5 text-sm px-2 py-2 dark:bg-gray-800">
                        {{ job.expression }}
                    </td>
                    <td v-if="showsColumn('nextDue')" class="w-1/5 px-2 py-2 dark:bg-gray-800">
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
import FetchesJobs from '../mixins/FetchesJobs'

const DEFAULT_COLUMNS = ['command', 'expression', 'nextDue']

export default {
    mixins: [FetchesJobs],

    props: ['card'],

    data: () => {
        return {
            jobs: [],
            loading: false,
        }
    },

    computed: {
        columns() {
            if (Array.isArray(this.card?.columns) && this.card.columns.length) {
                return this.card.columns
            }

            return DEFAULT_COLUMNS
        },
    },

    mounted() {
        this.fetchJobs()
    },

    methods: {
        showsColumn(column) {
            return this.columns.includes(column)
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
