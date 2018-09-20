<template>
    <modal @modal-close="handleClose">
        <form
            @submit.prevent="handleConfirm"
            slot-scope="props"
            class="bg-white rounded-lg shadow-lg overflow-hidden"
            style="width: 460px"
        >
            <slot :command="command">
                <div class="p-8">
                    <heading :level="2" class="mb-6">Dispatch - <b>{{ command }}</b></heading>
                    <p class="text-80 leading-normal">Are you sure you want to dispatch the Job?</p>
                </div>
            </slot>

            <div class="bg-30 px-6 py-3 flex">
                <div class="ml-auto">
                    <button type="button" data-testid="cancel-button" dusk="cancel-dispatch-job-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                    <button id="confirm-dispatch-job-button" ref="confirmButton" data-testid="confirm-button" type="submit" class="btn btn-default btn-primary">Dispatch</button>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
    export default {
        props: {
            command: String,
        },
        methods: {
            handleClose() {
                this.$emit('close')
            },
            handleConfirm() {
                this.$emit('confirm')
            },
        },
        mounted() {
            this.$refs.confirmButton.focus()
        },
    }
</script>
