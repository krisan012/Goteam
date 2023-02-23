<template>
    <div>
        <v-app-bar class="mb-10">
            <v-toolbar-title>My App</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn @click="fetchCurrentUser" size="x-large" variant="text" icon="fas fa-user"></v-btn>
            <v-btn @click="logout">
                Logout
                <v-icon>fa fa-arrow-right-from-bracket</v-icon>
            </v-btn>
        </v-app-bar>

        <v-dialog v-model="loading" :scrim="false" persistent width="auto">
            <v-card color="grey-lighten-5">
                <v-card-text>
                    Loading
                    <v-progress-linear indeterminate color="black" class="mb-0"></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>

        <profile-dialog :dialog.sync="dialog" :profile="profile" @update:show="closeProfileDialog" />
    </div>
</template>

<script>

import ProfileDialog from './ProfileDialog.vue';

export default {
    name: 'AppBar',

    components: {
        ProfileDialog,
    },

    data() {
        return {
            dialog: false,
            profile: {},
            loading: false,
        }
    },
    methods: {
        closeProfileDialog() {
            this.dialog = false;
        },

        logout() {
            this.$store.commit('unsetAuthenticated');
            this.$router.push('/login');
        },

        async fetchCurrentUser() {
            try {
                this.loading = true;
                const response = await axios.get('/get/current-user');
                const user = response.data;
                this.profile = user;
                this.dialog = true;
                this.loading = false
            } catch (error) {
                // Handle any errors that occurred
            }

        }
    }
}
</script>