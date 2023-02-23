<template>
    <v-container style="margin-inline: auto;max-width: 1320px;">
        <app-bar></app-bar>

        <pokemon-explorer />
        
    </v-container>
</template>
  
<script>

import PokemonExplorer from './PokemonExplorer.vue';
import AppBar from './AppBar.vue';

export default {
    name: 'Home',


    components: {
        PokemonExplorer,
        AppBar
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