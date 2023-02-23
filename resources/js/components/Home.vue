<template>
    <v-container style="margin-inline: auto;max-width: 1320px;">
        <v-app-bar class="mb-10">
            <v-toolbar-title>My App</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="fetchCurrentUser" size="x-large" variant="text" icon="fas fa-user"></v-btn>
            <v-btn @click="logout">Logout</v-btn>
        </v-app-bar>

        <pokemon-explorer />

        <profile-dialog :dialog.sync="dialog" :profile="profile" @update:show="closeProfileDialog" />

        <v-dialog v-model="loading" :scrim="false" persistent width="auto">
            <v-card color="grey-lighten-5">
                <v-card-text>
                    Loading
                    <v-progress-linear indeterminate color="black" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-container>
</template>
  
<script>

import PokemonExplorer from './PokemonExplorer.vue';
import ProfileDialog from './ProfileDialog.vue';

export default {
    name: 'Home',


    components: {
        PokemonExplorer,
        ProfileDialog,
    },

    data() {
        return {
            dialog: false,
            profile: {},
            loading: false,
        }
    },

    methods: {
        logout() {
            this.$store.commit('unsetAuthenticated');
            this.$router.push('/login');
        },

        closeProfileDialog() {
            this.dialog = false;
        },

        async fetchCurrentUser() {
            try {
                this.loading = true;
                const response = await axios.get('/get/current-user');
                const user = response.data;
                this.profile = user;
                this.dialog = true;
                this.loading = false
            } catch (error) {
                // Handle any errors that occurred
            }

        }
    },
};
</script>
  

<style>
.v-img img {
    object-fit: cover;
    width: 100%;
    height: 100%;
    vertical-align: middle;
    border-radius: 5px;

}
</style>