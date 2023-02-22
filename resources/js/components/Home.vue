<template>
    <v-container style="margin-inline: auto;max-width: 1320px;">
        <v-app-bar class="mb-10">
            <v-toolbar-title>My App</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="logout">Logout</v-btn>
        </v-app-bar>

        <pokemon-explorer />

    </v-container>
</template>
  
<script>

import PokemonExplorer from './PokemonExplorer.vue';

export default {
    name: 'Home',

    components: {
        PokemonExplorer
    },

    methods: {
        logout() {
            this.$store.commit('unsetAuthenticated');
            this.$router.push('/login');
        },
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