<template>
    <v-container class="fill-height">
        <v-row justify="center" align="center">
            <v-col cols="12" md="8" lg="4">
                <v-card class="elevation-12 text-right">
                    <v-card-title class="text-h5 text-center pa-10">
                        Login
                    </v-card-title>

                    <v-card-text>
                        <v-form @submit.prevent="login" ref="form">
                            <v-text-field v-model="email" class="email" label="Email" type="email" :rules="emailRules"
                                prepend-icon="fas fa-envelope" name="email"></v-text-field>
                            <v-text-field v-model="password" label="Password" type="password" :rules="passwordRules"
                                prepend-icon="fas fa-lock" name="password"></v-text-field>
                                <!-- <button>Login</button> -->
                            <v-btn id="login" type="submit" name="login" color="primary" class="mt-4 login">Login</v-btn>
                        </v-form>
                        <v-divider class="my-4"></v-divider>
                        <div class="text-center">
                            <span class="mr-2">Don't have an account?</span>
                            <router-link class="font-weight-bold" to="/register">Register</router-link>
                        </div>
                        <v-alert v-if="errorMessage" type="error" dismissible class="mt-4">
                            {{ errorMessage }}
                        </v-alert>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
 
<script>
import axios from 'axios';

export default {
    name: 'Login',

    data() {
        return {
            email: '',
            password: '',
            errorMessage: ''
        }
    },
    computed: {
        emailRules() {
            return [
                value => !!value || 'Required.',
                value => /.+@.+\..+/.test(value) || 'Email must be valid.'
            ];
        },
        passwordRules() {
            return [
                value => !!value || 'Required.',
                value => (value && value.length >= 8) || 'Password must be at least 8 characters.'
            ];
        }
    },

    methods: {
        async login() {
            try {
                const response = await axios.post('/login', {
                    email: this.email,
                    password: this.password
                });

                this.$store.commit('setAuthenticated', true);
                this.$store.commit('setAuthenticatedUser', response.data.user);
                // Redirect to the home page on successful login
                this.$router.push('/');
            } catch (error) {
                if (error.response.status === 401 || error.response.status === 422) {
                    this.errorMessage = 'Invalid email or password.';
                } else {
                    this.errorMessage = 'An error occurred. Please try again later.';
                }
            }

        },
    }
}
</script>
 
 
<style scoped>
.card {
    max-width: 400px;
    margin: auto;
}

.text-h5 {
    font-size: 1.5rem;
}

@media (min-width: 960px) {
    .card {
        margin-top: 50px;
    }
}
</style>