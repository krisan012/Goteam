import { createRouter, createWebHistory } from 'vue-router';
import {nextTick} from 'vue';
import Home from './components/Home.vue';
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import UserList from './components/UserList.vue';
import store from './store';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta: { requiresAuth: true, title: "Home" },
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresGuest: true, title: "Login" },
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { requiresGuest: true, title: "Register" },
  },
  {
    path: '/users',
    name: 'Users',
    component: UserList,
    meta: { requiresAuth: true, title: "Users" },
  }
];

const router = createRouter({
  history: createWebHistory('/'),
  routes,
});

const DEFAULT_TITLE = "Set";

router.afterEach((to) => {
  nextTick(() => {
    document.title = to.meta.title || DEFAULT_TITLE;
  });
});


router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.authenticated) {
    next({ name: 'Login' });
  } else if (to.meta.requiresGuest && store.state.authenticated) {
    next({ name: 'Home' });
  } else {
    next();
  }
});

export default router;

