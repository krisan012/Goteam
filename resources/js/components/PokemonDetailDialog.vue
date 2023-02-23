<template>
    <v-dialog v-model="showDialog" width="auto" transition="dialog-bottom-transition">
        

        <v-card width="660">
            <pokemon-detail :pokemon="pokemon"></pokemon-detail>
        </v-card>
    </v-dialog>
</template>

<script>
import PokemonDetail from './PokemonDetail.vue';
export default {
    components: {
        PokemonDetail
    },
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
                this.$emit('update:showpokemon', false);
            }
        },
    },
}
</script>