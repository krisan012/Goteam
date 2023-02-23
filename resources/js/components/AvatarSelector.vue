<template>
    <v-dialog v-model="dialog">
        <img :src="user.avatar" :alt="user.name" width="100" height="100">
        <input type="file" ref="fileInput" @change="onFileChange">
        <button @click="uploadAvatar">Save</button>
    </v-dialog>
</template>
  
<script>
export default {
    props: {
        dialog:{
            type:Boolean,
            required: true,
        },
        user: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            newAvatar: null
        };
    },
    methods: {
        onFileChange(event) {
            this.newAvatar = event.target.files[0];
        },
        async uploadAvatar() {
            const formData = new FormData();
            formData.append('avatar', this.newAvatar);
            try {
                const response = await axios.post('/user/avatar', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.$emit('avatar-updated', response.data.avatar_url);
            } catch (error) {
                console.error(error);
            }
        }
    }
};
</script>
  