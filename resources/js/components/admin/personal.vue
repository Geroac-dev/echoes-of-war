<template>
    <v-layout>
        <v-row>
            <v-col cols="8">
                <h1>
                    Personal
                </h1>
            </v-col>
            <v-col cols="4" align="right">
                <v-menu>
                    <template v-slot:activator="{props}">
                        <v-btn v-bind="props" variant="text" size="x-large">
                            <v-icon>
                                mdi-menu
                            </v-icon>
                        </v-btn>
                    </template>
                    <v-list>
                        <v-list-item @click="showAddDialog()">
                            <v-list-item-title>
                                <v-icon>
                                    mdi-account-plus
                                </v-icon>
                                Añadir
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-col>
            <v-col cols="12">
                <v-data-table
                    :items="personal"
                    :loading="loading"
                    :headers="headers"
                    >
                    <template v-slot:item.actions="{ item }">
                        <v-menu>
                            <template v-slot:activator="{props}">
                                <v-btn v-bind="props" variant="text">
                                    <v-icon>
                                        mdi-menu
                                    </v-icon>
                                </v-btn>
                            </template>
                            <v-list>
                                <v-list-item @click="showEditDialog(item)">
                                    <v-list-item-title>
                                        <v-icon>
                                            mdi-pencil
                                        </v-icon>
                                        Editar
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="showDeleteDialog(item)">
                                    <v-list-item-title>
                                        <v-icon>
                                            mdi-trash-can-outline
                                        </v-icon>
                                        Eliminar
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
        <v-dialog persistent v-model="personalDialog" max-width="900">
            <v-card>
                <v-card-title align="center" class="bg-primary">
                        {{ !selectedPersonal.id ? "Añadir" : "Editar" }} personal
                        <br v-if="mdAndDown">
                        <v-btn icon="mdi-close" variant="text" @click="personalDialog=false" />
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12" align="center">
                            <v-text-field
                                ref="name"
                                v-model="selectedPersonal.name"
                                label="Nombre"
                                prepend-inner-icon="mdi-text-short"
                                v-on:keyup.enter="$refs.user.focus()"
                            />
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-text-field
                                ref="user"
                                v-model="selectedPersonal.user"
                                label="Usuario"
                                prepend-inner-icon="mdi-account"
                                v-on:keyup.enter="$refs.pass.focus()"
                            />
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-text-field
                                ref="pass"
                                v-model="pass"
                                label="Contraseña"
                                prepend-inner-icon="mdi-form-textbox-password"
                                v-on:keyup.enter="$refs.email.focus()"
                            />
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-text-field
                                ref="email"
                                v-model="selectedPersonal.email"
                                label="Email"
                                prepend-inner-icon="mdi-email-outline"
                                v-on:keyup.enter="savepersonal()"
                            />
                        </v-col>
                        <v-col cols="12" align="center" v-if="!!errormsg">
                            <v-alert color="error">
                                {{ errormsg }}
                            </v-alert>
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-btn color="primary" @click="savepersonal()" block size="x-large">
                                <template v-if="loading">
                                    <v-progress-circular
                                        indeterminate
                                    />
                                </template>
                                <template v-else>
                                    Guardar
                                </template>
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="deleteDialog" max-width="900">
            <v-card>
                <v-card-text>
                    <v-row>
                        <v-col cols="12">
                            <v-alert variant="tonal" color="warning" icon="$warning"
                                :title="'¿Esta seguro de que desea eliminar a '+selectedPersonal.name+'?'" />
                        </v-col>
                        <v-col cols="12" md="6" align="center">
                            <v-btn variant="outlined" color="primary" @click="deleteDialog = false" block
                                size="x-large">
                                Cancelar
                            </v-btn>
                        </v-col>
                        <v-col cols="12" md="6" align="center">
                            <v-btn color="primary" @click="eliminar()" block size="x-large">
                                <template v-if="loading">
                                    <v-progress-circular indeterminate />
                                </template>
                                <template v-else>
                                    Eliminar
                                </template>
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-layout>
</template>

<script>
import { mapState } from "vuex";
import { useDisplay } from 'vuetify';

export default {
    setup() {
        const { mdAndDown } = useDisplay();
        return { mdAndDown };
    },
    mounted() {
        this.getPersonal();
    },
    data() {
        return {
            loading: true,
            personalDialog: false,
            deleteDialog: false,
            personal: [],
            headers: [
                { title: 'Nombre', key: 'name'},
                { title: 'Usuario', key: 'user'},
                { title: 'Email', key: 'email' },
                { title: 'Acciones', key: 'actions'}
            ],
            selectedPersonal: {},
            errormsg: null,
            pass: null,
            group: [],
        };
    },
    methods: {
        getPersonal() {
            axios.post("/get-users").then(({ data }) => {
                if (data.status == 200) {
                    this.personal = data.users;
                }
                this.loading = false;
            });
        },
        showAddDialog() {
            this.selectedPersonal = {};
            this.personalDialog = true;
        },
        showEditDialog(item) {
            this.selectedPersonal = item;
            this.personalDialog = true;
        },
        savepersonal() {
            if (!this.loading) {
                this.loading = true;
                if (!this.selectedPersonal.name) {
                    this.errormsg = "Llene el campo nombre.";
                    this.$refs.name.focus();
                    this.loading = false;
                } else if (!this.selectedPersonal.user) {
                    this.errormsg = "Llene el campo usuario.";
                    this.$refs.user.focus();
                    this.loading = false;
                } else if (!this.selectedPersonal.id && !this.pass) {
                    this.errormsg = "Llene el campo contraseña.";
                    this.$refs.name.focus();
                    this.loading = false;
                } else if (!this.selectedPersonal.email) {
                    this.errormsg = "Llene el campo email.";
                    this.$refs.email.focus();
                    this.loading = false;
                } else {
                    this.personal = [];
                    let route = null;
                    let id = null;
                    if (!this.selectedPersonal.id) {
                        route = "/save-user";
                    } else {
                        route = "/edit-user";
                        id = this.selectedPersonal.id;
                    }
                    axios.post(route, {
                        id: id,
                        name: this.selectedPersonal.name,
                        user: this.selectedPersonal.user,
                        email: this.selectedPersonal.email,
                        pass: this.pass,
                    }).then(({ data }) => {
                        if (data.status == 200) {
                            this.$store.dispatch("snackbar/setSnackbar", {text: !this.selectedPersonal.id ? "Personal añadido correctamente." : "Personal editado correctamente.", color:"success"});
                            this.selectedPersonal = {};
                            this.pass = null;
                            this.personalDialog = false;
                        } else if (data.status == 501) {
                            this.errormsg = "El nombre de usuario ya se encuentra en uso.";
                        } else {
                            this.errormsg = "Ha ocurrido un error, si el error persiste, contacte con soporte ;)";
                        }
                        this.getPersonal();
                        this.loading = false;
                    });
                }
            }
        },
        showDeleteDialog(item) {
            this.selectedPersonal = item;
            this.deleteDialog = true;
        },
        eliminar() {
            if (!this.loading) {
                this.loading = true;
                this.personal = [];
                axios.post("/delete-user", {
                    id: this.selectedPersonal.id,
                }).then(({ data }) => {
                    if (data.status == 200) {
                        this.$store.dispatch("snackbar/setSnackbar", {text: data.msg, color: data.color});
                        this.selectedPersonal = {};
                        this.deleteDialog = false;
                        this.getPersonal();
                    } else {
                        this.errormsg = "Ha ocurrido un error, si el error persiste, contacte con soporte ;)";
                    }
                    this.loading = false;
                });
                
            }
        }
    },
    computed: {
        ...mapState({
            user: state => state.users.user
        }),
    },
};
</script>