<template>
    <v-app>
        <v-app-bar color="primary">
            <v-btn text href="/" to="/" icon size="x-large" :class="mdAndDown?'ml-1':'ml-10'">
                <img src="/images/logo.png" style="width: 64px;" :draggable="false" @contextmenu.prevent>
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
                        <v-list-item v-bind="props" :title="locale === 'en' ? categoria.name : categoria.nombre" />
                    </template>
                    <v-list-item 
                        v-for="contenido in categoria.contenidos"
                        :key="contenido.id"
                        :title="locale === 'en' ? contenido.name : contenido.nombre"
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
                                        <v-list-item v-bind="props" :title="$t('admin')" />
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
                                        :title="$t('login')"
                                        href="/iniciar-sesion"
                                        to="/iniciar-sesion"
                                    />
                                    <v-list-item 
                                        v-else
                                        key="logout"
                                        :title="$t('logout')"
                                        @click="logout()"
                                    />
                            </v-list-group>
                        </v-list>
                    </v-col>
                    <v-divider />
                    <v-col cols="12" v-if="!!user">
                        <v-switch
                            class="ml-8"
                            v-model="darkMode"
                            inset
                            false-icon="mdi-white-balance-sunny"
                            true-icon="mdi-weather-night"
                        />
                    </v-col>
                    <v-col cols="12">
                        <v-switch
                            v-model="idiomaSwitch"
                            :label="idiomaSwitch ? 'English' : 'Español'"
                            inset
                            class="ml-8"
                            @change="cambiarIdioma"
                        />
                    </v-col>
                </v-row>
            </template>
        </v-navigation-drawer>
    </v-app>
</template>
<script>
    import { mapState } from "vuex";
    import { useDisplay } from 'vuetify';
    import { useI18n } from 'vue-i18n';
    import { ref } from 'vue';

    export default {
        setup() {
            const { mdAndDown } = useDisplay();
            const { locale } = useI18n();
            
            // Idioma
            const idiomaGuardado = localStorage.getItem('idioma');
            if (idiomaGuardado) {
                locale.value = idiomaGuardado;
            }
            const idiomaSwitch = ref(locale.value === 'en');

            return { mdAndDown, locale, idiomaSwitch };
        },
        mounted() {
            this.$store.dispatch("users/setUser");
            this.getCategorias();
        },
        data: () => ({
            darkMode: false,
            isInitializing: true,
            drawer: false,
            admin: [],
            categorias: [],
            cate: [],
            vistas: []
        }),
        methods: {
            logout() {
                this.axios.post("/logout")
                .then(({ data }) => {
                    this.$store.dispatch("users/setUser");
                    this.$router.push("/");
                    this.drawer = false;
                });
            },
            getCategorias() {
                axios.post("/get-categorias").then(({ data }) => {
                    if (data.status == 200) {
                        this.categorias = data.categorias;
                    }
                });
            },
            cambiarIdioma() {
                this.locale = this.idiomaSwitch ? 'en' : 'es';
                localStorage.setItem('idioma', this.locale);
            }
        },
        watch: {
            darkMode(val) {
                if (this.isInitializing) return;

                const themeInstance = this.$vuetify ? this.$vuetify.theme : null;
                if (themeInstance) {
                    themeInstance.change(val ? 'dark' : 'light');
                }

                if (!!this.user) {
                    axios.post("/change-theme", { theme: val });
                }
            },
            user(val) {
                if (!!val) {
                    this.isInitializing = true;
                    
                    const isDark = val.dark_mode === true || val.dark_mode === 'true' || val.dark_mode === 1;
                    
                    this.darkMode = isDark;
                    
                    const themeInstance = this.$vuetify ? this.$vuetify.theme : null;
                    if (themeInstance) {
                        themeInstance.change(isDark ? 'dark' : 'light');
                    }

                    this.$nextTick(() => {
                        this.isInitializing = false;
                    });
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