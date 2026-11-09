<template>
    <v-layout>
        <v-row>
            <v-col cols="8">
                <h1>
                    {{ $t('categories') }}
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
                                    mdi-folder-multiple-plus
                                </v-icon>
                                {{ $t('add') }}
                            </v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-col>
            <v-col cols="12">
                <v-data-table
                    :items="categorias"
                    :loading="loading"
                    :headers="headers"
                    show-expand
                    expand-strategy="single"
                >
                    <template #expanded-row="{ columns, item }">
                        <td :colspan="columns.length">
                            <v-data-table
                                v-if="item.contenidos.length > 0"
                                :items="item.contenidos"
                                :loading="loading"
                                :headers="headersContenidos"
                                hide-default-footer
                                :items-per-page="item.contenidos.length"
                                class="bg-background"
                            >
                                <template v-slot:item.imagen="{ item }">
                                    <v-img :src="'/storage/'+item.imagen" height="50" @click="imagen=item.imagen;imagenDialog=true" v-if="!!item.imagen"/>
                                    <template v-else>
                                        {{ $t('imageless') }}
                                    </template>
                                </template>
                                    <template v-slot:item.descripcion="{ item }">
                                        <span class="text-pre-wrap">{{ item.descripcion }}</span>
                                    </template>
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
                                            <v-list-item @click="showSeccionesDialog(item)">
                                                <v-list-item-title>
                                                    <v-icon>
                                                        mdi-animation-outline
                                                    </v-icon>
                                                    {{ $t('sections') }}
                                                    
                                                </v-list-item-title>
                                            </v-list-item>
                                            <v-list-item @click="showEditContenidoDialog(item)">
                                                <v-list-item-title>
                                                    <v-icon>
                                                        mdi-pencil
                                                    </v-icon>
                                                    {{ $t('edit') }}
                                                </v-list-item-title>
                                            </v-list-item>
                                            <v-list-item @click="showDeleteContenidoDialog(item)">
                                                <v-list-item-title>
                                                    <v-icon>
                                                        mdi-trash-can-outline
                                                    </v-icon>
                                                    {{ $t('delete') }}
                                                </v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </template>
                            </v-data-table>
                            <h4 v-else>
                                {{ $t('dataless') }}
                            </h4>
                        </td>
                    </template>
                    <template v-slot:item.descripcion="{ item }">
                        <span class="text-pre-wrap">{{ item.descripcion }}</span>
                    </template>
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
                                <v-list-item @click="showAddContenidoDialog(item)">
                                    <v-list-item-title>
                                        <v-icon>
                                            mdi-folder-multiple-plus
                                        </v-icon>
                                        {{ $t('new') }} {{ $t('content') }}
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="showEditDialog(item)">
                                    <v-list-item-title>
                                        <v-icon>
                                            mdi-pencil
                                        </v-icon>
                                        {{ $t('edit') }}
                                    </v-list-item-title>
                                </v-list-item>
                                <v-list-item @click="showDeleteDialog(item)">
                                    <v-list-item-title>
                                        <v-icon>
                                            mdi-trash-can-outline
                                        </v-icon>
                                        {{ $t('delete') }}
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>
                    </template>
                </v-data-table>
            </v-col>
        </v-row>
        <v-dialog persistent v-model="categoriaDialog" max-width="900">
            <v-card>
                <v-card-title align="center" class="bg-primary">
                        {{ !selectedcategoria.id ? $t('add') : $t('edit') }} {{ $t('categories') }}
                        <br v-if="mdAndDown">
                        <v-btn icon="mdi-close" variant="text" @click="categoriaDialog=false" />
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="6" align="center">
                            <v-text-field
                                ref="nombre"
                                v-model="selectedcategoria.nombre"
                                label="Nombre"
                                prepend-inner-icon="mdi-text-short"
                            />
                        </v-col>
                        <v-col cols="6" align="center">
                            <v-text-field
                                ref="name"
                                v-model="selectedcategoria.name"
                                label="Name"
                                prepend-inner-icon="mdi-text-short"
                            />
                        </v-col>
                        <v-col cols="6" align="center">
                            <v-textarea
                                ref="descripcion"
                                v-model="selectedcategoria.descripcion"
                                label="Descripcion"
                                prepend-inner-icon="mdi-text"
                            />
                        </v-col>
                        <v-col cols="6" align="center">
                            <v-textarea
                                ref="description"
                                v-model="selectedcategoria.description"
                                label="Description"
                                prepend-inner-icon="mdi-text"
                            />
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-text-field
                                ref="orden"
                                v-model="selectedcategoria.orden"
                                :label="$t('order')"
                                type="number"
                                prepend-inner-icon="mdi-filter-variant"
                            />
                        </v-col>
                        <v-col cols="12" align="center" v-if="!!errormsg">
                            <v-alert color="error">
                                {{ errormsg }}
                            </v-alert>
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-btn color="primary" @click="savecategoria()" block size="x-large">
                                <template v-if="loading">
                                    <v-progress-circular
                                        indeterminate
                                    />
                                </template>
                                <template v-else>
                                    {{ $t('save') }}                                    
                                </template>
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog persistent v-model="addContenidoDialog" max-width="900">
            <v-card>
                <v-card-title align="center" class="bg-primary">
                        {{ !selectedcontenido.id ? $t('add') : $t('edit') }} {{ $t('') }}
                        <br v-if="mdAndDown">
                        <v-btn icon="mdi-close" variant="text" @click="addContenidoDialog=false" />
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="6" align="center">
                            <v-text-field
                                ref="nombre"
                                v-model="selectedcontenido.nombre"
                                label="Nombre"
                                prepend-inner-icon="mdi-text-short"
                                v-on:keyup.enter="$refs.name.focus()"
                            />
                        </v-col>
                        <v-col cols="6" align="center">
                            <v-text-field
                                ref="name"
                                v-model="selectedcontenido.name"
                                label="Name"
                                prepend-inner-icon="mdi-text-short"
                                v-on:keyup.enter="$refs.descripcion.focus()"
                            />
                        </v-col>
                        <v-col cols="6" align="center">
                            <v-textarea
                                ref="descripcion"
                                v-model="selectedcontenido.descripcion"
                                label="Descripcion"
                                prepend-inner-icon="mdi-text"
                                v-on:keyup.enter="$refs.description.focus()"
                            />
                        </v-col>
                        <v-col cols="6" align="center">
                            <v-textarea
                                ref="description"
                                v-model="selectedcontenido.description"
                                label="Description"
                                prepend-inner-icon="mdi-text"
                                v-on:keyup.enter="$refs.user.focus()"
                            />
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-text-field
                                ref="orden"
                                v-model="selectedcontenido.orden"
                                :label="$t('order')"
                                type="number"
                                prepend-inner-icon="mdi-filter-variant"
                                v-on:keyup.enter="$refs.slug.focus()"
                            />
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-text-field
                                ref="slug"
                                v-model="selectedcontenido.slug"
                                label="Slug"
                                prepend-inner-icon="mdi-text-short"
                                v-on:keyup.enter="$refs.user.focus()"
                            />
                        </v-col>
                        <v-col cols="12">
                            <vc-drop-file @changed="fileChanged"/>
                        </v-col>
                        <v-col cols="12" align="center" v-if="!!errormsg">
                            <v-alert color="error">
                                {{ errormsg }}
                            </v-alert>
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-btn color="primary" @click="saveContenido()" block size="x-large">
                                <template v-if="loading">
                                    <v-progress-circular
                                        indeterminate
                                    />
                                </template>
                                <template v-else>
                                    {{ $t('save') }}
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
                                :title="$t('confirmDelete')+selectedcategoria.nombre+'?'" />
                        </v-col>
                        <v-col cols="12" md="6" align="center">
                            <v-btn variant="outlined" color="primary" @click="deleteDialog = false" block
                                size="x-large">
                                {{ $t('cancel') }}
                            </v-btn>
                        </v-col>
                        <v-col cols="12" md="6" align="center">
                            <v-btn color="primary" @click="eliminar()" block size="x-large">
                                <template v-if="loading">
                                    <v-progress-circular indeterminate />
                                </template>
                                <template v-else>
                                    {{ $t('delete') }}
                                </template>
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="deleteContenidoDialog" max-width="900">
            <v-card>
                <v-card-text>
                    <v-row>
                        <v-col cols="12">
                            <v-alert variant="tonal" color="warning" icon="$warning"
                                :title="$t('confirmDelete')+selectedcontenido.nombre+'?'" />
                        </v-col>
                        <v-col cols="12" md="6" align="center">
                            <v-btn variant="outlined" color="primary" @click="deleteContenidoDialog = false" block
                                size="x-large">
                                {{ $t('cancel') }}
                            </v-btn>
                        </v-col>
                        <v-col cols="12" md="6" align="center">
                            <v-btn color="primary" @click="eliminarContenido()" block size="x-large">
                                <template v-if="loading">
                                    <v-progress-circular indeterminate />
                                </template>
                                <template v-else>
                                    {{ $t('delete') }}
                                </template>
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog v-model="imagenDialog">
            <v-card>
                <v-card-title align="center" class="bg-primary">
                        {{ $t('image') }}
                        <br v-if="mdAndDown">
                        <v-btn icon="mdi-close" variant="text" @click="imagenDialog=false" />
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="12">
                            <v-img :src="'/storage/'+imagen"/>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-dialog persistent v-model="contenidoDialog" fullscreen>
            <v-card>
                <v-card-title align="center" class="bg-primary">
                        {{ $t('content') }}
                        <br v-if="mdAndDown">
                        <v-btn icon="mdi-close" variant="text" @click="contenidoDialog=false" />
                </v-card-title>
                <v-card-text class="bg-background">
                    <v-row>
                        <v-col cols="12" align="center">
                            <vc-secciones :contenido="selectedcontenido"/>
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
        this.getCategorias();
    },
    data() {
        return {
            loading: true,
            categoriaDialog: false,
            deleteDialog: false,
            deleteContenidoDialog: false,
            addContenidoDialog: false,
            contenidoDialog: false,
            imagenDialog: false,
            imagen: null,
            categorias: [],
            headers: [
                { title: 'Nombre', key: 'nombre'},
                { title: 'Name', key: 'name'},
                { title: 'Descripcion', key: 'descripcion'},
                { title: 'Description', key: 'description'},
                { title: 'actions', key: 'actions'}
            ],
            headersContenidos: [
                { title: 'Name', key: 'name'},
                { title: 'Nombre', key: 'nombre'},
                { title: 'Descripcion', key: 'descripcion'},
                { title: 'Description', key: 'description'},
                { title: 'Slug', key: 'slug'},
                { title: 'image', key: 'imagen'},
                { title: 'actions', key: 'actions'}
            ],
            selectedcategoria: {},
            selectedcontenido: {},
            errormsg: null,
            file: null,
        };
    },
    methods: {
        getCategorias() {
            this.loading = true;
            axios.post("/get-categorias").then(({ data }) => {
                if (data.status == 200) {
                    this.categorias = data.categorias;
                }
                this.loading = false;
            });
        },
        showAddDialog() {
            this.selectedcategoria = {};
            this.categoriaDialog = true;
        },
        showAddContenidoDialog(item) {
            this.selectedcategoria = item;
            this.selectedcontenido = {};
            this.addContenidoDialog = true;
        },
        showEditDialog(item) {
            this.selectedcategoria = item;
            this.categoriaDialog = true;
        },
        showSeccionesDialog(item) {
            this.selectedcontenido = item;
            this.contenidoDialog = true;
        },
        showEditContenidoDialog(item) {
            this.selectedcontenido = item;
            this.addContenidoDialog = true;
        },
        savecategoria() {
            if (!this.loading) {
                this.loading = true;
                if (!this.selectedcategoria.nombre || !this.selectedcategoria.name) {
                    this.errormsg = this.$t('fillField')+"nombre.";
                    this.$refs.name.focus();
                    this.loading = false;
                } else if (!this.selectedcategoria.descripcion || !this.selectedcategoria.description) {
                    this.errormsg = this.$t('fillField')+"descripcion.";
                    this.$refs.descripcion.focus();
                    this.loading = false;
                } else if (!this.selectedcategoria.orden) {
                    this.errormsg = this.$t('fillField')+"orden.";
                    this.$refs.orden.focus();
                    this.loading = false;
                } else {
                    this.categoria = [];
                    let route = null;
                    let id = null;
                    if (!this.selectedcategoria.id) {
                        route = "/save-categoria";
                    } else {
                        route = "/edit-categoria";
                        id = this.selectedcategoria.id;
                    }
                    axios.post(route, {
                        id: id,
                        nombre: this.selectedcategoria.nombre,
                        name: this.selectedcategoria.name,
                        descripcion: this.selectedcategoria.descripcion,
                        description: this.selectedcategoria.description,
                        orden: this.selectedcategoria.orden,
                    }).then(({ data }) => {
                        if (data.status == 200) {
                            this.$store.dispatch("snackbar/setSnackbar", {text: !this.selectedcategoria.id ? "categoria añadido correctamente." : "categoria editado correctamente.", color:"success"});
                            this.selectedcategoria = {};
                            this.categoriaDialog = false;
                            this.getCategorias();
                        } else if (data.status == 501) {
                            this.errormsg = "El nombre de usuario ya se encuentra en uso.";
                            this.loading = false;
                        } else {
                            this.errormsg = $t('errorMsg');
                            this.loading = false;
                        }
                    });
                }
            }
        },
        saveContenido() {
            if (!this.loading) {
                this.loading = true;
                if (!this.selectedcontenido.nombre || !this.selectedcontenido.name) {
                    this.errormsg = this.$t('fillField')+"nombre.";
                    this.$refs.name.focus();
                    this.loading = false;
                } else if (!this.selectedcontenido.descripcion || !this.selectedcontenido.description) {
                    this.errormsg = this.$t('fillField')+"descripcion.";
                    this.$refs.descripcion.focus();
                    this.loading = false;
                } else if (!this.selectedcontenido.orden) {
                    this.errormsg = this.$t('fillField')+"orden.";
                    this.$refs.orden.focus();
                    this.loading = false;
                } else if (!this.selectedcontenido.slug) {
                    this.errormsg = this.$t('fillField')+"slug.";
                    this.$refs.slug.focus();
                    this.loading = false;
                } else {
                    this.contenido = [];
                    let route = null;
                    let id = null;
                    if (!this.selectedcontenido.id) {
                        route = "/save-contenido";
                    } else {
                        route = "/edit-contenido";
                        id = this.selectedcontenido.id;
                    }
                    var formData = new FormData();
                    formData.append('id', id);
                    formData.append('categoria_id', this.selectedcategoria.id);
                    formData.append('nombre', this.selectedcontenido.nombre);
                    formData.append('name', this.selectedcontenido.name);
                    formData.append('descripcion', this.selectedcontenido.descripcion);
                    formData.append('description', this.selectedcontenido.description);
                    formData.append('orden', this.selectedcontenido.orden);
                    formData.append('slug', this.selectedcontenido.slug);
                    if (!!this.file) {
                        formData.append('file', this.file[0])
                    }
                    axios.post(route, formData).then(({ data }) => {
                        if (data.status == 200) {
                            this.$store.dispatch("snackbar/setSnackbar", {text: !this.selectedcategoria.id ? "contenido añadido correctamente." : "contenido editado correctamente.", color:"success"});
                            this.selectedcontenido = {};
                            this.selectedcategoria = {};
                            this.addContenidoDialog = false;
                            this.getCategorias();
                        } else if (data.status == 501) {
                            this.errormsg = "El nombre de usuario ya se encuentra en uso.";
                            this.loading = false;
                        } else {
                            this.errormsg = $t('errorMsg');
                            this.loading = false;
                        }
                    });
                }
            }
        },
        showDeleteDialog(item) {
            this.selectedcategoria = item;
            this.deleteDialog = true;
        },
        showDeleteContenidoDialog(item) {
            this.selectedcontenido = item;
            this.deleteContenidoDialog = true;
        },
        eliminar() {
            if (!this.loading) {
                this.loading = true;
                this.categoria = [];
                axios.post("/delete-categoria", {
                    id: this.selectedcategoria.id,
                }).then(({ data }) => {
                    if (data.status == 200) {
                        this.$store.dispatch("snackbar/setSnackbar", {text: data.msg, color: data.color});
                        this.selectedcategoria = {};
                        this.deleteDialog = false;
                        this.getCategorias();
                    } else {
                        this.errormsg = $t('errorMsg');
                        this.loading = false;
                    }
                });
                
            }
        },
        eliminarContenido() {
            if (!this.loading) {
                this.loading = true;
                this.categoria = [];
                axios.post("/delete-contenido", {
                    id: this.selectedcontenido.id,
                }).then(({ data }) => {
                    if (data.status == 200) {
                        this.$store.dispatch("snackbar/setSnackbar", {text: data.msg, color: data.color});
                        this.selectedcontenido = {};
                        this.deleteContenidoDialog = false;
                        this.getCategorias();
                    } else {
                        this.errormsg = $t('errorMsg');
                        this.loading = false;
                    }
                });
                
            }
        },
        fileChanged (value) {
            this.file = value;
        },
    },
    computed: {
        ...mapState({
            user: state => state.users.user
        }),
    },
};
</script>