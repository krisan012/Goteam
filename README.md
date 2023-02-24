
# Pokemon List
The project is a web application that utilizes the Pokemon API from https://pokeapi.co/ to fetch and display a list of Pokemons. The application requires users to register to enable them to like up to three Pokemons and dislike up to three Pokemons. The project uses state with the help of Vuex to manage and store data in the client-side. The frontend of the application is built using Vue.js, with Vuetify as the main CSS framework. Some additional Javascript frameworks such as Lodash and Vue3datepicker.com are also incorporated into the project.

On the server-side, the application uses Laravel as the main backend framework, which communicates with the frontend to store user data and manage server-side validation of user input. The project is developed by Mark Anthony Tuazon and is a part of his exam for the job application.

---
---
---
## Project Setup

 1. Copy the .env.example file to .env and fill in your MySQL details and the APP_URL environment variable.

2. Run the following commands:
   
```sh
composer install
php artisan migrate
php artisan storage:link
npm install
npm run build
```

* `composer install` installs the required PHP packages.
* `php artisan migrate` runs the database migrations to create the necessary tables.
* `php artisan storage:link` creates a symbolic link to the storage directory for public access to files.
* `npm install` installs the required Node.js packages.
* `npm run build` builds the Vue.js application for production.


---

## Testing

We use Laravel Dusk for testing. Run the following commands to set up Dusk and run the tests:

```sh
composer require --dev laravel/dusk
php artisan dusk:install
php artisan dusk
```

---

## Route

```javascript
const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: { requiresAuth: true, title: 'Home' },
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresGuest: true, title: 'Login' },
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { requiresGuest: true, title: 'Register' },
  },
  {
    path: '/users',
    name: 'Users',
    component: UserList,
    meta: { requiresAuth: true, title: 'Users' },
  }
];
```

* The `Home` path displays the list of Pokemon, and infinite scroll automatically fetches new records once the user reaches the bottom of the page.
* The `Users` path shows all users with their liked and disliked Pokemon.

## Node.js Packages

* "axios": "^1.3.3"
* "laravel-vite-plugin": "^0.7.2"
* "vite": "^4.0.0"
* "@vitejs/plugin-vue": "^4.0.0"
*  "@vuepic/vue-datepicker": "^4.0.1"
*  "@vueuse/head": "^1.0.26"
*   "lodash": "^4.17.21"
*   "vue": "^3.2.36"
*   "vue-router": "^4.0.13"
*   "vuetify": "^3.1.4"
*   "vuex": "^4.0.2"

## Composer Packages
```php
"require": {
        "php": "^8.1",
        "guzzlehttp/guzzle": "^7.2",
        "laravel/framework": "^10.0",
        "laravel/sanctum": "^3.2",
        "laravel/tinker": "^2.8"
    },
    "require-dev": {
        "fakerphp/faker": "^1.9.1",
        "laravel/dusk": "^7.7",
        "laravel/pint": "^1.0",
        "laravel/sail": "^1.18",
        "mockery/mockery": "^1.4.4",
        "nunomaduro/collision": "^7.0",
        "phpunit/phpunit": "^10.0",
        "spatie/laravel-ignition": "^2.0"
    },
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
