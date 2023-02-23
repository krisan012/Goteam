<template>
    <v-container style="margin-inline: auto;max-width: 1320px;">
        <v-toolbar>
            <v-toolbar-title>Users</v-toolbar-title>
        </v-toolbar>
        <v-row dense>
            <v-col v-for="user in users.data" :key="user" lg="3" md="4" sm="6">
                <v-card color="grey-lighten-5" class="mt-5" v-ripple.center @click="profileSelected(user)">
                    <div class="d-flex flex-no-wrap justify-space-between">
                        <div>
                            <v-card-title class="text-sm pb-0" style="max-width: 167px;">
                                {{ user.name }}
                            </v-card-title>

                            <v-card-subtitle>{{ user.birthday }}</v-card-subtitle>

                            <v-card-actions class="pb-0">
                                <div class="v-btn v-btn--icon v-theme--light v-btn--density-default v-btn--size-default v-btn--variant-text ms-2"
                                    v-for="like in user.likes" :key="like.id">
                                    <v-img style="width: 50px" color="red"
                                        :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${like.pokemon_id}.png`"></v-img>
                                </div>
                                <!-- <v-btn class="ms-2" icon="fa fa-play" variant="text"></v-btn>
                                <v-btn class="ms-2" icon="fa fa-heart" variant="text"></v-btn> -->
                                <!-- <v-btn class="ms-2" icon="fa fa-pause" variant="text"></v-btn> -->
                            </v-card-actions>

                            <v-divider v-if="user.dislikes.length >= 1" style="margin: 0 20px"></v-divider>
                            
                            <v-card-actions class="pt-0 ">
                                <div class="v-btn v-btn--icon v-theme--light v-btn--density-default v-btn--size-default v-btn--variant-text ms-2"
                                    v-for="dislike in user.dislikes" :key="dislike.id">
                                    <v-img style="width: 50px" color="red"
                                        :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${dislike.pokemon_id}.png`"></v-img>
                                </div>
                                <!-- <v-img class="bg-white" 
                                    :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/1.png`"></v-img> -->
                                <!-- <v-btn class="ms-2" icon="fa fa-heart" variant="text"></v-btn> -->
                                <!-- <v-btn class="ms-2" icon="fa fa-pause" variant="text"></v-btn> -->
                            </v-card-actions>
                        </div>

                        <v-avatar class="ma-3" size="125" rounded="0">
                            <v-img :src="user.avatar"></v-img>
                        </v-avatar>
                    </div>
                </v-card>
            </v-col>
        </v-row>

        <v-row justify="center" v-show="loading">
            <v-col cols="4" color="grey-lighten-5">
                <v-card class="pa-2 ma-2">
                    <li class="blog-post o-media">

                        <div class="o-media__body">
                            <div class="o-vertical-spacing">
                                <h3 class="blog-post__headline">
                                    <span class="skeleton-box" style="width:55%;"></span>
                                </h3>
                                <p>
                                    <span class="skeleton-box" style="width:80%;"></span>
                                    <span class="skeleton-box" style="width:90%;"></span>
                                </p>
                                <div class="blog-post__meta">
                                    <span class="skeleton-box" style="width:70px;"></span>
                                </div>
                            </div>
                        </div>
                        <div class="o-media__figure">
                            <span class="skeleton-box" style="width:100px;height:80px;"></span>
                        </div>
                    </li>
                </v-card>
            </v-col>
        </v-row>


        <profile-dialog :dialog.sync="dialog" :profile="profileSelect" @update:show="closeProfileDialog" />
        <!-- <template>
            <v-dialog v-model="dialog" width="auto">

                <v-card>
                    <v-card-text>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore
                        et
                        dolore magna aliqua.
                    </v-card-text>
                    <v-card-actions>
                        <v-btn color="primary" block @click="dialog = false">Close Dialog</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </template> -->


        <v-card v-intersect="loadMore"></v-card>
    </v-container>
</template>
  
<script>
import axios from 'axios';
import ProfileDialog from './ProfileDialog.vue';

export default {
    components: {
        ProfileDialog,
    },
    data() {
        return {
            users: [],
            profileSelect: {},
            page: 1,
            loading: true,
            lastpage: 1,
            dialog: false
        };
    },
    mounted() {
        this.fetchUsers();
    },
    methods: {
        fetchUsers() {
            // Fetch the list of users from the server
            this.loading = true;
            axios.get('/get/users')
                .then(response => {
                    // Update the users array with the fetched data
                    console.log(response)
                    this.users = response.data.data
                    this.loading = false;
                    this.lastpage = response.data.data.last_page
                })
                .catch(error => {
                    console.error(error);
                });
        },

        async loadMore() {
            // If we're already loading, don't load more data
            if (this.loading) {
                return;
            }

            if (this.page > this.lastpage)
                return;

            // Set loading state to true
            this.loading = true;

            // Fetch the next page of users from the API
            const nextPage = this.page + 1;
            const response = await axios.get(`/get/users?page=${nextPage}`);

            // Append the new users to the existing users array
            this.users.data.push(...response.data.data.data);

            // Update the page number
            this.page = nextPage;

            // Set loading state to false
            this.loading = false;
        },

        profileSelected(profile) {
            this.profileSelect = profile;
            this.dialog = true;
        },
        closeProfileDialog() {
            this.dialog = false;
        }

    },
};
</script>
  

<style>
.skeleton-box {
    display: inline-block;
    height: 1em;
    position: relative;
    overflow: hidden;
    background-color: #dddbdd;
}

.skeleton-box::after {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    transform: translateX(-100%);
    background-image: linear-gradient(90deg, rgba(255, 255, 255, 0) 0, rgba(255, 255, 255, 0.2) 20%, rgba(255, 255, 255, 0.5) 60%, rgba(255, 255, 255, 0));
    animation: shimmer 2s infinite;
    content: '';
}

@keyframes shimmer {
    100% {
        transform: translateX(100%);
    }
}

.blog-post__headline {
    font-size: 1.25em;
    font-weight: bold;
}

.blog-post__meta {
    font-size: 0.85em;
    color: #6b6b6b;
}

.o-media {
    display: flex;
}

.o-media__body {
    flex-grow: 1;
    margin-left: 1em;
}

.o-vertical-spacing>*+* {
    margin-top: 0.75em;
}

.o-vertical-spacing--l>*+* {
    margin-top: 2em;
}
</style>