<template>
    <v-container class="pa-0">
        <v-card>
            <v-img
                :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${pokemon.id}.png`"
                :lazy-src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${pokemon.id}.png`"
                aspect-ratio="1" cover class="bg-grey-lighten-2">
                <template v-slot:placeholder>
                    <v-row class="fill-height ma-0" align="center" justify="center">
                        <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                    </v-row>
                </template>
            </v-img>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-list-item-action>
                    <v-btn text :color="pokemon.liked ? '#09f' : '#424242'" @click.stop="likePokemon(pokemon)">
                        <v-icon small>fa fa-thumbs-up</v-icon>
                        {{ pokemon.likes_count }}
                    </v-btn>
                </v-list-item-action>

                <v-btn size="small" color="surface-variant" variant="text" icon="fas fa-heart"></v-btn>

                <v-btn size="small" color="surface-variant" variant="text" icon="fa fa-thumbs-down"></v-btn>


                <!-- <v-btn size="small" color="surface-variant" variant="text" icon="share-variant"></v-btn> -->
            </v-card-actions>
        </v-card>
    </v-container>
</template>
  
<script>
import axios from 'axios';
import { isNil } from 'lodash';

export default {
    name: 'PokemonDetail',
    props: {
        pokemon: {
            type: Object,
            required: true,
        },
    },

    computed: {
        imagefetched() {
            return isNil(this.pokemon.likes_fetch);
        },
    },

    // data() {
    //     return {
    //         color: this.pokemon.liked ? "#09f" : "#424242",
    //     };
    // },

    methods: {
        likePokemon(pokemon) {
            if (pokemon.liked) {
                // Remove the like if the user has already liked the Pokemon
                axios.delete(`/likes/${pokemon.like_id}`).then(() => {
                    pokemon.liked = false;
                    pokemon.likes_count--;
                });
            } else {
                // Add a new like if the user has not yet liked the Pokemon
                axios.post(`/likes/${pokemon.id}`).then((response) => {
                    pokemon.liked = true;
                    pokemon.likes_count++;
                    pokemon.like_id = response.data.id;
                });
            }
        },
    }

    // mounted() {
    //     axios.get(this.pokemonUrl)
    //         .then(response => {
    //             this.pokemon = response.data;
    //         })
    //         .catch(error => {
    //             console.log(error);
    //         });
    // },
};
</script>
  