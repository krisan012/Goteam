<template>
    <v-dialog v-model="dialog" persistent width="auto" :fullscreen="isFullScreen" transition="dialog-bottom-transition">

        <v-card class="profile-card">
            <v-toolbar prominent extended>
                <v-btn icon @click="showProfile = false">
                    <v-icon>fa fa-arrow-left</v-icon>
                </v-btn>

                <v-toolbar-title>{{ currentUser.name }}</v-toolbar-title>

                <v-spacer></v-spacer>

                <v-btn icon v-if="parseInt(profile.id) == parseInt(this.$store.state.authId)"
                    @click="openUserDetailEditDialog">
                    <v-icon>fa fa-keyboard</v-icon>
                </v-btn>
            </v-toolbar>

            <v-row justify="center" style="max-height: 220px;">
                <v-col cols="12">
                    <v-avatar v-if="isCurrentUser" size="90" style="top: -30px;display: block; margin: 0 auto;cursor: pointer;" class="elevation-20"
                        @click="triggerFileUpload">
                        <v-img :src="newAvatar" height="200px" cover></v-img>
                        <input type="file" ref="fileInput" @change="onFileChange">
                    </v-avatar>
                    <v-avatar v-else size="90" style="top: -30px;display: block; margin: 0 auto;" class="elevation-20">
                        <v-img :src="newAvatar" height="200px" cover></v-img>
                    </v-avatar>

                    <v-list-item :title="currentUser.name" :subtitle="currentUser.birthday" class="text-center pt-0"
                        style="top:-20px"></v-list-item>
                </v-col>
            </v-row>

            <v-divider style="margin: 0 50px"></v-divider>

            <v-card-title>User Liked Pokemons</v-card-title>

            <div class="ma-5 text-center">
                <v-row justify="center" dense>

                    <v-col v-if="parseInt(profile.id) == parseInt(this.$store.state.authId)" cols="12" lg="4" md="4" sm="6"
                        v-for="like in userLikes" :key="like.id" class="profile-pokemon">
                        <v-avatar size="120" class="elevation-5 ml-2 mr-2 zoom" @click="FetchPokemonData(like.pokemon_id)"
                            style="cursor: pointer;">
                            <v-img
                                :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${like.pokemon_id}.png`"
                                height="120px" contain></v-img>
                        </v-avatar>
                    </v-col>

                    <v-col v-else cols="12" lg="4" md="4" sm="6" v-for="like in profile.likes" :key="like.pokemon_id"
                        class="profile-pokemon">
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
                    <v-col v-if="parseInt(profile.id) == parseInt(this.$store.state.authId)" cols="12" lg="4" md="4" sm="6"
                        v-for="dislike in userDislikes" :key="dislike.id" class="profile-pokemon"
                        @click="FetchPokemonData(dislike.pokemon_id)" style="cursor: pointer;">
                        <v-avatar size="120" class="elevation-5 zoom">
                            <!-- <v-tooltip activator="parent" location="end">{{ like.name }}</v-tooltip> -->
                            <v-img
                                :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${dislike.pokemon_id}.png`"
                                height="80px" contain></v-img>
                        </v-avatar>
                    </v-col>

                    <v-col v-else cols="12" lg="4" md="4" sm="6" v-for="dislike in profile.dislikes"
                        :key="dislike.pokemon_id" class="profile-pokemon" @click="FetchPokemonData(dislike.pokemon_id)"
                        style="cursor: pointer;">
                        <v-avatar size="120" class="elevation-5 zoom">
                            <!-- <v-tooltip activator="parent" location="end">{{ like.name }}</v-tooltip> -->
                            <v-img
                                :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${dislike.pokemon_id}.png`"
                                height="80px" contain></v-img>
                        </v-avatar>
                    </v-col>

                </v-row>
            </div>

            <pokemon-detail-dialog :dialog.sync="pokemonDialog" :pokemon="pokemonData"
                @update:showpokemon="closePokemonDialog" />

            <edit-user-detail-dialog :editDialog.sync="userDetailEditDialog" :user="profile"
                @update:showeditdialog="closeUserDetailEditDialog" />
        </v-card>
    </v-dialog>
</template>

<script>
import PokemonDetailDialog from './PokemonDetailDialog.vue';
import EditUserDetailDialog from './EditUserDetailDialog.vue';
import { mapGetters } from "vuex";

export default {
    components: {
        PokemonDetailDialog,
        EditUserDetailDialog
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

        isCurrentUser(){
            return parseInt(this.profile.id) == parseInt(this.$store.state.authId)
        },

        currentUser(){
            return this.isCurrentUser ? this.$store.state.authenticatedUser : this.profile
        }
    },

    data() {
        return {
            innerWidth: window.innerWidth,
            isFullScreen: window.innerWidth <= 760,
            pokemonData: {},
            pokemonDialog: false,
            userDetailEditDialog: false,
            newAvatar: this.$store.state.authenticatedUser.avatar,
            base64Img: null,
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


        openUserDetailEditDialog() {
            this.userDetailEditDialog = true;
        },

        closeUserDetailEditDialog() {
            this.userDetailEditDialog = false;
        },

        onFileChange(event) {
            this.newAvatar = event.target.files[0];

            this.uploadAvatar();
        },
        async uploadAvatar() {
            const formData = new FormData();
            formData.append('avatar', this.newAvatar);
            try {
                const response = await axios.post('/user/avatar/update', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                this.$store.commit('setAuthenticatedUser', response.data.user);
                this.newAvatar = response.data.user.avatar
                // response.data.avatar_url
            } catch (error) {
                console.error(error);
            }
        },

        triggerFileUpload() {
            this.$refs.fileInput.click()
        }
    }

}
</script>

<style scoped>
.profile-card {
    min-width: 450px;
}

@media (max-width: 720px) {
    .profile-card {
        width: 100vw;
    }
}


.zoom {
    transition: transform .2s;
}

.zoom:hover {
    background: #fff;
    transform: scale(1.5);
    /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}

.profile-pokemon {
    min-width: 160px;
}
</style>