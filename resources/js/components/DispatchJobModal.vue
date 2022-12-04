<template>
    <Modal :show="show" role="alertdialog" size="md">
        <form
            @submit.prevent="handleConfirm"
            class="mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden"
        >
            <slot>
                <ModalHeader>
                    {{ __("Dispatch Job") }}
                </ModalHeader>
                <ModalContent>
                    <p class="mb-2 leading-normal">
                        {{
                            __(
                                "Are you sure you want to dispatch the following Job?"
                            )
                        }}
                    </p>
                    <p class="font-bold">{{ command }}</p>
                </ModalContent>
            </slot>

            <ModalFooter>
                <div class="ml-auto">
                    <LinkButton
                        type="button"
                        data-testid="cancel-button"
                        dusk="cancel-delete-button"
                        @click.prevent="handleClose"
                        class="mr-3"
                    >
                        {{ __("Cancel") }}
                    </LinkButton>

                    <LoadingButton
                        ref="confirmButton"
                        dusk="confirm-delete-button"
                        :processing="working"
                        :disabled="working"
                        component="DefaultButton"
                        type="submit"
                    >
                        {{ __("Confirm") }}
                    </LoadingButton>
                </div>
            </ModalFooter>
        </form>
    </Modal>
</template>

<script>
import startCase from "lodash/startCase";

export default {
    emits: ["confirm", "close"],

    props: {
        show: { type: Boolean, default: false },
        command: { type: String, default: "" },
    },

    data: () => ({
        working: false,
    }),

    methods: {
        handleClose() {
            this.$emit("close");
            this.working = false;
        },

        handleConfirm() {
            this.$emit("confirm");
            this.working = true;
        },
    },

    /**
     * Mount the component.
     */
    mounted() {
        this.$nextTick(() => {
            // this.$refs.confirmButton.button.focus()
        });
    },
};
</script>
