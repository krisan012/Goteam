<template>
    <v-row justify="center">
        <v-dialog v-model="showEditDialog" persistent width="1024">
            <v-card>
                <v-card-title>
                    <span class="text-h5">User Profile</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field label="first name *" v-model="first_name" required></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                                <v-text-field label="last name *" v-model="last_name"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field v-model="email" label="Email"></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field v-model="password" label="Password" type="password"></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <VueDatePicker v-model="birthday" no-today :enable-time-picker="false" model-type="yyyy-MM-dd" auto-apply></VueDatePicker>
                            </v-col>
                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="showEditDialog = false">
                        Close
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" @click="saveUser">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-row>
</template>

<script>

export default {
    props: {
        editDialog: {
            type: Boolean,
            required: true
        },
        user: {
            type: Object,
            required: true
        },
    },

    data() {
        return {
            email: this.user.email,
            password: this.user.password,
            first_name: this.user.first_name,
            last_name: this.user.last_name,
            birthday: this.user.birthday
        }
    },


    computed: {
        showEditDialog: {
            get() {
                return this.editDialog;
            },
            set(value) {
                this.$emit('update:showeditdialog', false);
            }
        },
    },

    methods: {
        async saveUser() {
            const data = {
                id: this.user.id,
                email: this.email,
                password: this.password,
                first_name: this.first_name,
                last_name: this.last_name,
                birthday: this.birthday
            };
            const response = await axios.post('/user/save', data);

            if (response.status === 201) {
                this.$store.commit('setAuthenticatedUser', response.data.user);
            } else {
                throw new Error('Unable to save user data');
            }
        },

    }
}
</script>