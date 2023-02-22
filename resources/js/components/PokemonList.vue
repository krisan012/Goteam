<template>
    <v-container>
        <v-row class="mx-lg-auto">
            <v-col v-for="pokemon in pokemonList" :key="pokemon.name" @click="selectPokemon(pokemon)"
                class="d-flex child-flex pa-2" cols="20" lg="3" md="4" sm="6" align-self="center">
                <pokemon-detail :pokemon="pokemon" />

            </v-col>
        </v-row>


        <!-- <div class="text-center" style="position: relative;">
            <v-overlay contained :model-value="loading" class="align-center justify-center">
                <v-progress-circular :size="50" color="primary" indeterminate size="64"></v-progress-circular>
            </v-overlay>

            <v-pagination prev-icon="fa fa-chevron-left" next-icon="fa fa-chevron-right" v-model="page"
                :length="Math.ceil(count / 20)" :total-visible="5" @click="fetchPokemonList(page)" />
        </div> -->

        <v-card v-intersect="loadMore"></v-card>
    </v-container>

    <!-- <div class="column">
        <div class="photo">
            <v-img src="https://unsplash.com/photos/tCbpaUma2cQ" class="bg-grey-lighten-2"></v-img>
        </div>
        <div class="photo">
            <v-img src="https://unsplash.com/photos/M176ghBQhC0" class="bg-grey-lighten-2"></v-img>
        </div>
        <div class="photo">
            <v-img src="https://unsplash.com/photos/hoUDqHwhhfw" class="bg-grey-lighten-2"></v-img>
        </div>
        <div class="photo">
            <v-img src="https://unsplash.com/photos/3Ijc0AQz1DY" class="bg-grey-lighten-2"></v-img>
        </div>
        <div class="photo">
            <v-img src="https://unsplash.com/photos/xJjzPaZnNdQ" class="bg-grey-lighten-2"></v-img>
        </div>
    </div> -->
    <!-- <v-container style="position: relative;" class="mt-20">
        <v-list>
            <v-list-subheader>Pokemon List</v-list-subheader>
            <v-list-item v-for="pokemon in pokemonList" :key="pokemon.name" @click="selectPokemon(pokemon)">
                <v-list-item-title>{{ pokemon.name }}</v-list-item-title>
            </v-list-item>
        </v-list>
        <v-overlay contained :model-value="loading" class="align-center justify-center">
            <v-progress-circular color="primary" indeterminate size="64"></v-progress-circular>
        </v-overlay>
        <v-pagination v-model="page" :length="Math.ceil(count / 20)" :total-visible="5" @click="fetchPokemonList(page)" />
    </v-container> -->
</template>
  
  
<script>
import axios from 'axios';
import PokemonDetail from './PokemonDetail.vue';
import { isNil } from 'lodash';

export default {
    name: 'PokemonList',
    components: {
        PokemonDetail,
    },
    data() {
        return {
            pokemonList: [],
            page: 1,
            count: 0,
            loading: false,
        };
    },

    mounted() {
        this.fetchPokemonListAndWait(this.page);
    },

    methods: {

        // fetchPokemonList(page) {
        //     this.loading = true;
        //     axios
        //         .get(`https://pokeapi.co/api/v2/pokemon?limit=20&offset=${(page - 1) * 20}`)
        //         .then((response) => {
        //             this.pokemonList = response.data.results.map((pokemon) => ({
        //                 id: pokemon.url.match(/\/(\d+)\//)[1],
        //                 name: pokemon.name,
        //                 likes_count: 0,
        //                 liked: false,
        //                 like_id: null,
        //                 url: pokemon.url
        //             }));

        //             // Fetch the current user's likes for the Pokemon in the list
        //             axios
        //                 .get('/user/likes', {
        //                     params: {
        //                         pokemon_ids: this.pokemonList.map((pokemon) => pokemon.id).join(','),
        //                     },
        //                 })
        //                 .then((response) => {
        //                     const likes = response.data;
        //                     this.pokemonList.forEach((pokemon) => {
        //                         const like = likes.find((like) => like.pokemon_id == pokemon.id);
        //                         if (like) {
        //                             pokemon.liked = true;
        //                             pokemon.likes_count = 1;
        //                             pokemon.like_id = like.id;
        //                         }
        //                     });
        //                 });
        //         })
        //         .catch((error) => {
        //             console.log(error);
        //         })
        //         .finally(() => {
        //             this.loading = false;
        //         });
        // },

        async fetchPokemonListAndWait(page) {
            await new Promise((resolve, reject) => {
                this.fetchPokemonList(page, resolve, reject);
            });
            // Execute other code after fetchPokemonList is completed
        },

        fetchPokemonList(page) {
            this.loading = true;
            axios
                .get(`https://pokeapi.co/api/v2/pokemon?limit=20&offset=${(page - 1) * 20}`)
                .then(response => {
                    this.count = response.data.count;
                    this.pokemonList = response.data.results.map((pokemon) => ({
                        id: pokemon.url.match(/\/(\d+)\//)[1],
                        name: pokemon.name,
                        likes_count: 0,
                        liked: false,
                        like_id: null,
                        url: pokemon.url
                    }));

                    // Fetch the likes count for the Pokemon in the list


                    this.fetchPokemonLikeCount();


                })
                .catch((error) => {
                    console.log(error);
                })
                .finally(() => {
                    this.loading = false;
                    this.page++;
                });
        },

        fetchPokemonLikeCount() {
            axios
                .all(
                    this.pokemonList.map((pokemon) =>
                        axios.get(`/pokemon/${pokemon.id}/likes/count`)
                    )
                )
                .then(
                    axios.spread((...responses) => {
                        responses.forEach((response, index) => {
                            this.pokemonList[index].likes_count = response.data.count;
                            if (!isNil(response.data.like)) {
                                this.pokemonList[index].liked = response.data.like.type == 1 ? true : false;
                                this.pokemonList[index].liked_type = response.data.like.type; //1 = like 0 = dislike
                                this.pokemonList[index].like_id = response.data.like.id;
                            }

                        });
                    })
                );
        },


        async loadMore() {

            if (this.page == 1)
                return;

            await setTimeout(async () => {
                await this.loadPokemon(this.page);
            }, 500)

        },

        async loadPokemon(page) {
            try {
                const response = await axios.get(`https://pokeapi.co/api/v2/pokemon?limit=20&offset=${(page - 1) * 20}`);

                const newPokemons = response.data.results.map((pokemon) => ({
                    id: pokemon.url.match(/\/(\d+)\//)[1],
                    name: pokemon.name,
                    likes_count: 0,
                    liked: false,
                    like_id: null,
                    url: pokemon.url
                }));

                this.pokemonList = [...this.pokemonList, ...newPokemons];
                this.page ++
            } catch (error) {
                console.log(error);
            }
        },

        selectPokemon(pokemon) {
            this.$emit('select', pokemon);
        },
    },

};
</script>
  