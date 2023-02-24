<template>
    <v-container class="pa-0">
        <v-hover v-slot="{ isHovering, props }">

            <v-card :elevation="isHovering ? 12 : 2" :class="{ 'on-hover': isHovering }" v-bind="props">
                <v-img
                    :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/${pokemon.id}.png`"
                    :lazy-src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${pokemon.id}.png`"
                    aspect-ratio="1" cover class="bg-grey-lighten-2 pokemon-img">
                    <template v-slot:placeholder>
                        <v-row class="fill-height ma-0" align="center" justify="center">
                            <v-progress-circular indeterminate color="grey-lighten-5"></v-progress-circular>
                        </v-row>
                    </template>
                    <div class="align-self-center action-center">
                        <v-btn size="x-large" :class="{ 'show-btns': isHovering }"
                            :color="(isHovering ? (isLiked ? '#09f' : '#424242') : this.transparent)" variant="text"
                            icon="fas fa-heart" @click.stop="likePokemon(pokemon)"></v-btn>
                        <v-btn size="x-large" :class="{ 'show-btns': isHovering }"
                            :color="(isHovering ? (isDisliked ? '#09f' : '#424242') : this.transparent)" variant="text"
                            icon="fas fa-thumbs-down" @click.stop="dislikePokemon(pokemon)"></v-btn>
                    </div>
                </v-img>
            </v-card>
        </v-hover>

        <v-snackbar v-model="snackbar" :timeout="2000" :color="snackbarColor">
            {{ text }}

            <template v-slot:actions>
                <v-btn color="pink" variant="text" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
    </v-container>
</template>
  
<script>
import axios from 'axios';
import { isNil, includes, toInteger, find } from 'lodash';
import { mapGetters, mapMutations } from "vuex";

export default {
    name: 'PokemonDetail',
    props: {
        pokemon: {
            type: Object,
            required: true
        },
    },

    computed: {
        ...mapGetters(["userLikes", "userDislikes"]),
        isLiked() {
            return includes(this.userLikes.map(like => like.pokemon_id), toInteger(this.pokemon.id));
        },
        isDisliked() {
            return includes(this.userDislikes.map(like => like.pokemon_id), toInteger(this.pokemon.id));
        },
        likeType() {
            const like = this.isLiked;
            const dislike = this.isDisliked;
            if (like) {
                return 1; //like
            } else if (dislike) {
                return 0; //dislike
            }
            else {
                return null;
            }
        },
        likeId() {
            const like = this.isLiked;
            const dislike = this.isDisliked;
            if (like) {
                return find(this.userLikes, { pokemon_id: toInteger(this.pokemon.id) }).id; //like
            } else if (dislike) {
                return find(this.userDislikes, { pokemon_id: toInteger(this.pokemon.id) }).id; //dislike
            } else {
                return null;
            }

        }

    },

    data() {
        return {
            snackbarColor: "",
            snackbar: false,
            text: '',
            transparent: 'rgba(255, 255, 255, 0)',

        };
    },

    methods: {
        ...mapMutations(["ADD_NEW_USER_LIKE", "ADD_NEW_USER_DISLIKE", "REMOVE_LIKE", "REMOVE_DISLIKE"]),

        likePokemon(pokemon) {
            if (this.likeType == 1) {
                // Remove the like if the user has already liked the Pokemon
                axios.delete(`/likes/${this.likeId}`).then(() => {
                    const id = toInteger(pokemon.id);
                    this.REMOVE_LIKE(id)
                });
            } else {

                // Add a new like if the user has not yet liked the Pokemon
                axios.post(`/likes`, {
                    type: 1, //like
                    pokemon_id: pokemon.id
                }).then((response) => {
                    const likeObject = {
                        id: toInteger(response.data.like_id),
                        pokemon_id: toInteger(pokemon.id),
                        type: 1
                    }

                    this.ADD_NEW_USER_LIKE(likeObject);
                    this.REMOVE_DISLIKE(likeObject.pokemon_id);

                    this.text = `You Added "${pokemon.name}" as your Pokemon`;
                    this.snackbar = true;
                    this.snackbarColor = "green";
                }).catch((error) => {
                    this.text = error.response.data.message;
                    this.snackbar = true;
                    this.snackbarColor = "red";
                })
            }
        },

        dislikePokemon(pokemon) {
            if (this.likeType == 0) {
                axios.delete(`/likes/${this.likeId}`).then(() => {
                    const id = toInteger(pokemon.id);
                    this.REMOVE_DISLIKE(id);
                });
            }
            else {
                axios.post(`/likes`, {
                    type: 0, //dislike
                    pokemon_id: toInteger(pokemon.id)
                }).then((response) => {
                    const likeObject = {
                        id: toInteger(response.data.like_id),
                        pokemon_id: toInteger(pokemon.id),
                        type: 0
                    }

                    this.ADD_NEW_USER_DISLIKE(likeObject);
                    this.REMOVE_LIKE(likeObject.pokemon_id)

                    this.text = `You Added "${pokemon.name}" as your hated Pokemon`;
                    this.snackbar = true;
                    this.snackbarColor = "grey";
                })
                    .catch((error) => {
                        this.text = error.response.data.message;
                        this.snackbar = true;
                        this.snackbarColor = "red";
                    })
            }
        }
    }
};
</script>
  
<style scoped>
.v-card {
    transition: opacity .4s ease-in-out;
}

.v-card:not(.on-hover) {
    opacity: 0.6;
}

.show-btns {
    color: rgb(70, 70, 70);
}

.action-center {
    margin: 0px;
    position: absolute;
    top: 50%;
    left: 30%;
    transform: translateY(-50%);
}
</style>