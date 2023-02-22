<template>
    <v-container class="fill-height">
      <v-row justify="center" align="center">
        <v-col cols="12" md="8">
          <v-card>
            <v-card-title class="text-h5">
              Register
            </v-card-title>
  
            <v-card-text>
              <v-form @submit.prevent="register" ref="form">
                <v-text-field v-model="name" label="Name" :rules="nameRules"></v-text-field>
                <v-text-field v-model="email" label="Email" type="email" :rules="emailRules"></v-text-field>
                <v-text-field v-model="password" label="Password" type="password" :rules="passwordRules"></v-text-field>
                <v-text-field v-model="password_confirmation" label="Confirm Password" type="password" :rules="confirmPasswordRules"></v-text-field>
                <v-btn type="submit" color="primary">Register</v-btn>
              </v-form>
              <v-alert v-if="errorMessage" type="error" dismissible>
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
    name: 'Register',
  
    data() {
      return {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        nameRules: [
          v => !!v || 'Name is required',
        ],
        emailRules: [
          v => !!v || 'Email is required',
          v => /.+@.+/.test(v) || 'Email must be valid',
        ],
        passwordRules: [
          v => !!v || 'Password is required',
          v => v.length >= 6 || 'Password must be at least 6 characters',
        ],
        confirmPasswordRules: [
          v => !!v || 'Confirm Password is required',
          v => v === this.password || 'Passwords must match',
        ],
        errorMessage: '',
      }
    },
  
    methods: {
      async register() {
        const { valid } = await this.$refs.form.validate()

        if (!valid) return;
  
        axios.post('/register', {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        })
        .then(response => {
          console.log(response);
          // Redirect to the login page on successful registration
          this.$router.push('/login');
        })
        .catch(error => {
          console.log(error);
          // Show an error message to the user
          this.errorMessage = error.response.data.message;
        });
      },
    },
  }
  </script>
  
  <style scoped>
  /* Add your component-specific styles here */
  </style>
  