import moment from 'moment'

export default {

    methods: {

        formatNextRunAt(date) {
            return moment(date).fromNow().replace(/^\w/, c => c.toUpperCase())
        }

    }

}