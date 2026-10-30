<template>
    <v-app>
        <v-app-bar color="primary">
            <v-btn text href="/" to="/" icon size="x-large" :class="mdAndDown?'ml-1':'ml-10'">
                <h1>Logo?</h1>
            </v-btn>
            <v-spacer/>
            <v-app-bar-nav-icon variant="text" @click.stop="drawer = !drawer" />
        </v-app-bar>
        <v-row style="margin-top:75px" class="px-5">
            <v-col cols="0" md="1" lg="2"/>
            <v-col cols="12" md="10" lg="8" style="padding: 0; margin: 0;">
                <router-view />
            </v-col>
        </v-row>
        <v-snackbar v-model="snackbar" :color="color" class="pa-3">
            <h2 class="text-white">
                {{ text }}
            </h2>
        </v-snackbar>
        <v-navigation-drawer
            v-model="drawer"
            location="right"
            temporary
        >
            <v-list v-model:opened="cate">
                <v-list-group :value="categoria.id" v-for="categoria in categorias" :key="categoria.id">
                    <template v-slot:activator="{ props }">
                        <v-list-item v-bind="props" :title="categoria.nombre" />
                    </template>
                    <v-list-item 
                        v-for="contenido in categoria.contenidos"
                        :key="contenido.id"
                        :title="contenido.nombre"
                        :href="'/ver/'+contenido.slug"
                        :to="'/ver/'+contenido.slug"
                    />
                </v-list-group>
            </v-list>
            <template v-slot:append>
                <v-row>
                    <v-col cols="12">
                        <v-list v-model:opened="admin">
                            <v-list-group value="Administrar">
                                    <template v-slot:activator="{ props }">
                                        <v-list-item v-bind="props" title="Administrar" />
                                    </template>
                                    <v-list-item 
                                        v-if="!!user"
                                        key="administrar"
                                        title="Tabs"
                                        href="/administrar"
                                        to="/administrar"
                                    />
                                    <v-list-item 
                                        v-if="!user"
                                        key="login"
                                        title="Iniciar Sesión"
                                        href="/iniciar-sesion"
                                        to="/iniciar-sesion"
                                    />
                                    <v-list-item 
                                        v-else
                                        key="logout"
                                        title="Cerrar Sesión"
                                        @click="logout()"
                                    />
                            </v-list-group>
                        </v-list>
                    </v-col>
                    <template v-if="!!user">
                        <v-divider />
                        <v-col cols="12">
                            <v-switch
                            class="ml-8"
                            v-model="darkMode"
                            inset
                            :label="darkMode ? 'Modo claro' : 'Modo oscuro'"
                            />
                        </v-col>
                    </template>
                </v-row>
            </template>
        </v-navigation-drawer>
    </v-app>
</template>
<script>
import { mapState } from "vuex";
import { useDisplay } from 'vuetify';
import { useTheme } from 'vuetify';

export default {
    setup() {
        const { mdAndDown } = useDisplay();
        return { mdAndDown };
    },
    mounted() {
        this.$store.dispatch("users/setUser");
        this.theme = useTheme();
        this.getCategorias();
    },
    data: () => ({
        darkMode: false,
        theme: null,
        drawer: false,
        admin: [],
        categorias: [],
        cate: [],
        vistas: [],
    }),
    methods: {
        logout() {
            this.axios.post("/logout");
            this.$store.dispatch("users/setUser");
            this.$router.push("/");
            this.drawer = false;
        },
        getCategorias() {
            axios.post("/get-categorias").then(({ data }) => {
                if (data.status == 200) {
                    this.categorias = data.categorias;
                }
                this.loading = false;
            });
        },
    },
    watch: {
        darkMode(val) {
            if (this.theme) {
                if (val) {
                    this.theme.change('dark');
                } else {
                    this.theme.change('light');
                }
                if (!!this.user) {
                    axios.post("/change-theme", { theme: val });
                }
            }
        },
        user(val) {
            if (!!val) {
                this.darkMode = !!val.dark_mode;
                this.getCategorias();
            }
        }
    },
    computed: {
        ...mapState({
            user: state => state.users.user,
            snackbar: state => state.snackbar.snackbar,
            color: state => state.snackbar.color,
            text: state => state.snackbar.text
        }),
    },
};
</script>
<style>
    .v-application {
        height: 100%;
        overflow: clip;
    }
</style>