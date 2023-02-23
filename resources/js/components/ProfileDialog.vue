<template>
    <v-dialog v-model="dialog" persistent width="auto" :fullscreen="isFullScreen" transition="dialog-bottom-transition">

        <v-card>
            <v-toolbar prominent extended>
                <v-btn icon @click="showProfile = false">
                    <v-icon>fa fa-arrow-left</v-icon>
                </v-btn>

                <v-toolbar-title>{{ profile.name }}</v-toolbar-title>

                <v-spacer></v-spacer>

                <v-btn icon>
                    <v-icon>fa fa-keyboard</v-icon>
                </v-btn>
            </v-toolbar>

            <v-row justify="center">
                <v-col cols="12">
                    <v-avatar size="90" style="top: -30px;display: block; margin: 0 auto;" class="elevation-20">
                        <v-img src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg" height="200px" cover></v-img>
                    </v-avatar>

                    <v-list-item :title="profile.name" :subtitle="profile.birthday" class="text-center pt-0"
                        style="top:-20px"></v-list-item>
                </v-col>
            </v-row>

            <v-divider style="margin: 0 50px"></v-divider>

            <v-card-title>User Liked Pokemons</v-card-title>

            <div class="ma-5 text-center">
                <v-row justify="center" dense>

                    <v-col v-if="parseInt(profile.id) == parseInt(this.$store.state.authId)" cols="12" lg="4" md="4" sm="6" v-for="like in userLikes" :key="like.id">
                        <v-avatar size="120" class="elevation-5 ml-2 mr-2 zoom" @click="FetchPokemonData(like.pokemon_id)"
                            style="cursor: pointer;">
                            <v-img
                                :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${like.pokemon_id}.png`"
                                height="120px" contain></v-img>
                        </v-avatar>
                    </v-col>

                    <v-col v-else cols="12" lg="4" md="4" sm="6" v-for="like in profile.likes" :key="like.pokemon_id">
                        <v-avatar size="120" class="elevation-5 ml-2 mr-2 zoom" @click="FetchPokemonData(like.pokemon_id)"
                            style="cursor: pointer;">
                            <v-img
                                :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${like.pokemon_id}.png`"
                                height="120px" contain></v-img>
                        </v-avatar>
                    </v-col>
                </v-row>
            </div>

            <v-divider style="margin: 0 50px"></v-divider>

            <v-card-title>User Hated Pokemons</v-card-title>

            <div class="ma-5 mb-15 text-center">
                <v-row justify="center" dense>
                    <v-col cols="12" lg="4" md="4" sm="6" v-for="dislike in userDislikes" :key="dislike.id">
                        <v-avatar size="120" class="elevation-5 zoom">
                            <!-- <v-tooltip activator="parent" location="end">{{ like.name }}</v-tooltip> -->
                            <v-img
                                :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${dislike.pokemon_id}.png`"
                                height="80px" contain></v-img>
                        </v-avatar>
                    </v-col>

                </v-row>
            </div>

            <pokemon-detail-dialog :dialog.sync="pokemonDialog" :pokemon="pokemonData" @update:showpokemon="closePokemonDialog" />

        </v-card>
    </v-dialog>
</template>

<script>
import PokemonDetailDialog from './PokemonDetailDialog.vue';
import { mapGetters } from "vuex";

export default {
    components: {
        PokemonDetailDialog,
    },
    props: {
        profile: {
            type: Object,
            required: true
        },
        dialog: {
            type: Boolean,
            required: true
        },
    },

    mounted() {
        this.$nextTick(() => {
            window.addEventListener('resize', this.onResize);
        })
    },

    beforeDestroy() {
        window.removeEventListener('resize', this.onResize);
    },
    computed: {
        ...mapGetters(["userLikes", "userDislikes"]),
        showProfile: {
            get() {
                return this.dialog;
            },
            set(value) {
                this.$emit('update:show', false);
            }
        },
    },

    data() {
        return {
            innerWidth: window.innerWidth,
            isFullScreen: window.innerWidth <= 760,
            pokemonData: {},
            pokemonDialog: false,
        }
    },

    methods: {
        onResize() {
            this.innerWidth = window.innerWidth
            this.isFullScreen = this.innerWidth <= 760
        },

        async FetchPokemonData(pokemon_id) {
            const response = await axios.get(`https://pokeapi.co/api/v2/pokemon/${pokemon_id}`);
            this.pokemonData = response.data
            this.pokemonDialog = true;
        },

        closePokemonDialog() {
            this.pokemonDialog = false;
        },
    }

}
</script>

<style>
.profile-card {
    min-width: 450px;
}

.zoom {
    transition: transform .2s;
}

.zoom:hover {
    background: #fff;
    transform: scale(1.5);
    /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>