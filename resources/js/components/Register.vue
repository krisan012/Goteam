<template>
  <v-container class="fill-height" fluid>
    <v-row justify="center" align="center">
      <v-col cols="12" md="8" lg="4">
        <v-card class="text-right">
          <v-card-title class="text-h5 text-center pa-10">
            Register
          </v-card-title>

          <v-card-text>
            <v-form @submit.prevent="register" ref="form">
              <v-row>
                <v-col cols="6">
                  <v-text-field v-model="first_name" label="First Name" :rules="firstNameRules"
                    prepend-icon="fas fa-user"></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field v-model="last_name" label="Last Name" :rules="lastNameRules"
                    prepend-icon="fas fa-user"></v-text-field>
                </v-col>
              </v-row>
              <v-text-field v-model="email" label="Email" type="email" :rules="emailRules"
                prepend-icon="fas fa-envelope"></v-text-field>
              <v-text-field v-model="password" label="Password" type="password" :rules="passwordRules"
                prepend-icon="fas fa-lock"></v-text-field>
              <v-text-field v-model="password_confirmation" label="Confirm Password" type="password"
                :rules="confirmPasswordRules" prepend-icon="fas fa-lock"></v-text-field>
              <v-btn type="submit" color="primary" class="mt-4">Register</v-btn>
            </v-form>
            <v-divider class="my-4"></v-divider>
            <div class="text-center">
              <span class="mr-2">Already Registered?</span>
              <router-link class="font-weight-bold" to="/login">Login</router-link>
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
  name: 'Register',

  data() {
    return {
      first_name: '',
      last_name: '',
      email: '',
      password: '',
      password_confirmation: '',
      firstNameRules: [
        v => !!v || 'Name is required',
      ],
      lastNameRules: [
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
        first_name: this.first_name,
        last_name: this.last_name,
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
  