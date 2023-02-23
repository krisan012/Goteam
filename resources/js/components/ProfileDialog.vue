<template>
    <v-dialog v-model="showProfile" width="auto">

        <v-container>

            <v-card color="grey-lighten-4 profile-card" flat rounded="0">
                <v-toolbar prominent extended>
                    <v-btn icon @click="showProfile = false">
                        <v-icon>fa fa-arrow-left</v-icon>
                    </v-btn>

                    <v-toolbar-title>{{ profile.name }}</v-toolbar-title>

                    <v-spacer></v-spacer>

                    <v-btn icon>
                        <v-icon>fa fa-heart</v-icon>
                    </v-btn>
                </v-toolbar>
                <v-row justify="center">
                    <v-col cols="12">
                        <v-avatar size="90" style="top: -30px;display: block; margin: 0 auto;" class="elevation-20">
                            <v-img src="https://cdn.vuetifyjs.com/images/cards/sunshine.jpg" height="200px" cover></v-img>
                        </v-avatar>

                        <v-list-item :title="profile.name" :subtitle="profile.birthday" class="text-center pt-0"
                            style="top:-20px"></v-list-item>
                    </v-col>
                </v-row>

                <v-divider style="margin: 0 50px"></v-divider>

                <v-card-title>User Liked Pokemons</v-card-title>

                <div class="ma-5 text-center">
                    <v-avatar size="120" v-for="like in profile.likes" :key="like.id" class="elevation-5 ml-2 mr-2 zoom">
                        <!-- <v-tooltip activator="parent" location="end">{{ like.name }}</v-tooltip> -->
                        <v-img
                            :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${like.pokemon_id}.png`"
                            height="120px" contain></v-img>
                    </v-avatar>

                </div>

                <v-divider style="margin: 0 50px"></v-divider>

                <v-card-title>User Hated Pokemons</v-card-title>

                <div class="ma-5 mb-15 text-center">
                    <v-avatar size="120" v-for="dislike in profile.dislikes" :key="dislike.id"
                        class="elevation-5 ml-2 mr-2 zoom">
                        <!-- <v-tooltip activator="parent" location="end">{{ like.name }}</v-tooltip> -->
                        <v-img
                            :src="`https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/${dislike.pokemon_id}.png`"
                            height="80px" contain></v-img>
                    </v-avatar>

                </div>

            </v-card>
        </v-container>
    </v-dialog>
</template>

<script>
export default {
    props: {
        profile: {
            type: Object,
            required: true
        },
        dialog: {
            type: Boolean,
            required: true
        },
    },
    computed: {
        showProfile: {
            get() {
                return this.dialog;
            },
            set(value) {
                this.$emit('update:show', false);
            }
        }

    }
}
</script>

<style>
.profile-card{
    min-width: 450px;
}
.zoom {
    transition: transform .2s;
}

.zoom:hover {
    background: #fff;
    transform: scale(1.5);
    /* (150% zoom - Note: if the zoom is too large, it will go outside of the viewport) */
}
</style>