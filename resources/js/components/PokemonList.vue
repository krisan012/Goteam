<template>
    <v-container>
        <v-row class="mx-lg-auto">
            <v-col v-for="pokemon in pokemonList" :key="pokemon.name" @click="selectPokemon(pokemon)"
                class="d-flex child-flex pa-2" cols="20" lg="3" md="4" sm="6" align-self="center">
                <pokemon-detail :pokemon="pokemon" />

            </v-col>
        </v-row>

        <v-row justify="center" v-show="loading">
            <v-col cols="4" color="bg-grey-lighten-2">
                <v-card class="pa-2 ma-2" color="bg-grey-lighten-2">
                    <li class="blog-post o-media">

                        <div class="o-media__body">
                            <div class="o-media__figure">
                                <span class="skeleton-box"></span>
                            </div>
                        </div>

                    </li>
                </v-card>
            </v-col>
        </v-row>

        <v-card v-intersect="loadMore"></v-card>
    </v-container>
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


        async fetchPokemonListAndWait(page) {
            await new Promise((resolve, reject) => {
                this.fetchPokemonList(page, resolve, reject);
            });
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
                this.loading = true;
                await this.loadPokemon(this.page);
                this.loading = false;
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
                this.page++
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
  

<style scoped>
.skeleton-box {
    display: block;
    width: 250px;
    height: 160px;
    margin: 0 auto;
}
</style>