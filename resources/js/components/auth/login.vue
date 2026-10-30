<template>
    <v-card class="elevation-0 pa-3 mt-5 mx-auto" max-width="500px">
        <v-card-title class="flex-column d-flex justify-center align-center">
            <h1>Logo?</h1>
        </v-card-title>
        <v-card-text>
            <v-row>
                <v-col cols="12">
                    <v-text-field
                        ref="user"
                        v-model="user"
                        autofocus
                        type="user"
                        autocomplete="false"
                        label="Usuario"
                        v-on:keyup.enter="$refs.pass.focus()"
                    />
                </v-col>
                <v-col cols="12">
                    <v-text-field
                        v-model="pass"
                        ref="pass"
                        label="Contraseña"
                        autocomplete="false"
                        @click:append-inner="show = !show"
                        :type="show ? 'text' : 'password'"
                        :append-inner-icon="show ? 'mdi-eye' : 'mdi-eye-off'" 
                        v-on:keyup.enter="login()"
                    />
                </v-col>
            </v-row>
            <v-alert class="rounded-lg mb-4" v-if="error" :type="color">
                {{ msg }}
            </v-alert>
            <v-row>
                <v-col cols="12">
                    <v-btn @click="login()" color="primary" class="mb-3 elevation-0 rounded-lg" block size="x-large" :loading="loading">
                        Iniciar Sesión
                    </v-btn>
                </v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>
<script>
import _ from "lodash";
export default {
    data() {
        return {
            loading: false,
            show: false,
            user: "",
            pass: "",
            color: "",
            error: false,
            msg: ""
        };
    },
    methods: {
        login() {
            if(this.loading){
                return;
            }
            if (this.user == "") {
                this.error = true;
                this.color = "warning";
                this.msg = "Llene el campo correo para continuar.";
                this.$refs.user.focus();
                return;
            }
            if (this.pass == "") {
                this.error = true;
                this.color = "warning";
                this.msg = "Llene el campo contraseña para continuar.";
                this.$refs.pass.focus();
                return;
            }
            this.loading = true;
            axios.post("/login", {
                user: this.user,
                password: this.pass
            }).then(({ data }) => {
                if (data.status == "200") {
                    let rol = data.rol;
                    axios.get('/csrf-reload').then(({ data }) => {
                        if (data.csrfToken) {
                            axios.defaults.headers.common["X-CSRF-TOKEN"] = data.csrfToken;
                        }
                        this.$store.dispatch("users/setUser");
                        this.$router.push("/administrar");
                    });
                } else {
                    this.loading = false;
                    this.error = true;
                    this.color = "error";
                    this.msg = data.msg;
                }
            });
        },
        easter(){
            if (this.user == "tetris") {
                window.location.replace("tris");
            }
        }
    },
};
</script>