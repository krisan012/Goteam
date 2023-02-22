import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';

import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import * as directives from 'vuetify/directives'
import { aliases, md } from 'vuetify/iconsets/md'


const vuetify = createVuetify({
    components,
    directives,
})



const app = createApp(App);

app.config.globalProperties.$fetchUserLikes = async function () {
    try {
        const response = await axios.get("/user/likes");

        // Update the Vuex store with the user's likes and dislikes
        store.commit("SET_USER_LIKES", response.data.likes);
        store.commit("SET_USER_DISLIKES", response.data.dislikes);
    } catch (error) {
        console.log(error);
    }
};

app.use(router)
    .use(store)
    .use(vuetify)
    .mount('#app');


app.config.globalProperties.$fetchUserLikes();


