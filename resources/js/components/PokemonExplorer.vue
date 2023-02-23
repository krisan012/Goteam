<template>
    <v-container class="pa-0 mt-15">
        <pokemon-list @select="selectPokemon" />
    </v-container>
</template>
  
<script>
import PokemonList from './PokemonList.vue';

export default {
    name: 'PokemonExplorer',
    components: {
        PokemonList,
    },
    data() {
        return {
            selectedPokemonUrl: null,
        };
    },
    methods: {
        async selectPokemon(pokemon) {
            await axios.get(pokemon.url)
                .then(response => {
                    this.selectedPokemonUrl = response.data;
                    this.selectedPokemonUrl.likes_count = pokemon.likes_count;

                    axios.get("/pokemon/find/like/" + pokemon.id)
                        .then(response => {
                            this.selectedPokemonUrl.liked = response.data.like;
                            this.selectedPokemonUrl.like_id = response.data.like.id;
                        });

                })
                .catch(error => {
                    console.log(error);
                });
        },
    },
};
</script>
  