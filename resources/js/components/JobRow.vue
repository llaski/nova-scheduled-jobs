<template>
    <tr>
        <td>{{ job.command }}</td>
        <td class="py-2">{{ job.description }}</td>
        <td class="py-2">{{ job.humanReadableExpression }}</td>
        <td>{{ job.expression }}</td>
        <td>{{ formatNextRunAt(job.nextRunAt) }}</td>
        <td>{{ job.withoutOverlapping ? 'Yes' : 'No' }}</td>
        <td>{{ job.onOneServer ? 'Yes' : 'No' }}</td>
        <td>{{ job.evenInMaintenanceMode ? 'Yes' : 'No' }}</td>
    </tr>
</template>

<script>
export default {

    props: ['job'],

    methods: {

        formatNextRunAt(date) {
            return moment(date).fromNow().replace(/^\w/, c => c.toUpperCase())
        }

    }

}
</script>
