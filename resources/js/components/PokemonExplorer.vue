<template>
    <v-container class="pa-0 mt-15">
        
            
                <pokemon-list @select="selectPokemon" />
            
            <!-- <v-col v-for="n in 9" :key="n" class="d-flex child-flex" cols="12" md="4" sm="6">
                <v-img :src="`https://picsum.photos/500/300?image=${n * 5 + 10}`"
                    :lazy-src="`https://picsum.photos/10/6?image=${n * 5 + 10}`" aspect-ratio="1" cover
                    class="bg-grey-lighten-2">
                    <template v-slot:placeholder>
                        <v-row class="fill-height ma-0" align="center" justify="center">
                            <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                        </v-row>
                    </template>
                </v-img>
            </v-col> -->
       
        <!-- <v-row>
            <v-col cols="6">
                <pokemon-list @select="selectPokemon" />
            </v-col>
            <v-col cols="6" v-if="selectedPokemonUrl">
                <pokemon-detail :pokemon="selectedPokemonUrl" />
            </v-col>
        </v-row> -->
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
  