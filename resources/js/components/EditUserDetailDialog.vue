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
                    <v-btn color="blue-darken-1" :loading="loading" variant="text" @click="saveUser">
                        Save
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-snackbar v-model="snackbar" :timeout="2000" color="green">
            Successfully Updated!

            <template v-slot:actions>
                <v-btn color="pink" variant="text" @click="snackbar = false">
                    Close
                </v-btn>
            </template>
        </v-snackbar>
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
            birthday: this.user.birthday,
            loading: false,
            snackbar: false,
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
            this.loading = true;
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
                this.snackbar = true;
            } else {
                throw new Error('Unable to save user data');
            }
            this.loading = false;
        },

    }
}
</script>