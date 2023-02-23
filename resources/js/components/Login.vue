<template>
    <v-container class="fill-height">
        <v-row justify="center" align="center">
            <v-col cols="12" md="8">
                <v-card>
                    <v-card-title class="text-h5">
                        Login
                    </v-card-title>
 
                    <v-card-text>
                        <v-form @submit.prevent="login" ref="form">
                            <v-text-field v-model="email" label="Email" type="email" :rules="emailRules"></v-text-field>
                            <v-text-field v-model="password" label="Password" type="password" :rules="passwordRules"></v-text-field>
                            <v-btn type="submit" color="primary">Login</v-btn>
                        </v-form>
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
            emailRules: [
                v => !!v || 'Email is required',
                v => /.+@.+/.test(v) || 'Email must be valid',
            ],
            passwordRules: [
                v => !!v || 'Password is required',
                v => v.length >= 6 || 'Password must be at least 6 characters',
            ],
        }
    },
 
    methods: {
        async login() {
 
            const { valid } = await this.$refs.form.validate()

            if (!valid) return;

            axios.post('/login', {
                email: this.email,
                password: this.password,
            })
            .then(response => {
                this.$store.commit('setAuthenticated', true);
                this.$store.commit('setAuthenticatedUser', response.data.user);
                // Redirect to the home page on successful login
                this.$router.push('/');
            })
            .catch(error => {
                // Show an error message to the user
                alert('Invalid email or password.');
            });
        },
    },
 }
 </script>
 