<template>
    <v-dialog v-model="dialog" persistent width="auto" transition="dialog-bottom-transition">
        <v-btn color="blue-darken-1" variant="text" @click="showDialog = false">
            Close
        </v-btn>
    </v-dialog>
</template>

<script>

export default {
    props: {
        pokemon: {
            type: Object,
            required: true
        },
        dialog: {
            type: Boolean,
            required: true
        },
    },

    computed: {
        showDialog: {
            get() {
                return this.dialog;
            },
            set(value) {
                this.$emit('update:show', false);
            }
        },
    },
}
</script>