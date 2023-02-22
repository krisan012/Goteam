import axios from 'axios';

export default {
    state: {
        userLikes: [],
        userDislikes: []
    },

    mutations: {
        SET_USER_LIKES(state, likes) {
            state.userLikes = likes;
        },
        SET_USER_DISLIKES(state, dislikes) {
            state.userDislikes = dislikes;
        },
        ADD_NEW_USER_LIKE(state, like) {
            state.userLikes.push(like);
        },
        ADD_NEW_USER_DISLIKE(state, like) {
            state.userDislikes.push(like);
        },
        REMOVE_LIKE(state, pokemonId) {
            state.userLikes = state.userLikes.filter((dislike) => dislike.pokemon_id !== pokemonId);
        },
        REMOVE_DISLIKE(state, pokemonId) {
            state.userDislikes = state.userDislikes.filter((dislike) => dislike.pokemon_id !== pokemonId);
        }

    },

    actions: {
        async fetchUserLikes({ commit }) {
            try {
                const response = await axios.get('/api/likes/user');
                const likes = response.data.filter(like => like.type === 1);
                const dislikes = response.data.filter(like => like.type === 0);
                commit('SET_USER_LIKES', likes);
                commit('SET_USER_DISLIKES', dislikes);
            } catch (error) {
                console.error(error);
            }
        }
    },

    getters: {
        userLikes: state => state.userLikes,
        userDislikes: state => state.userDislikes
    }
};
