<template>
	<v-layout>
		<v-row>
			<v-col cols="12" :md="!!contenido.imagen?7:12">
				<v-card>
					<v-card-title>
						<v-row>
							<v-col cols="12">
								<h1>
									{{ locale === 'en' ? contenido.name : contenido.nombre }}
								</h1>
							</v-col>
							<v-col cols="12">
								<h4 class="text-wrap">
									{{ locale === 'en' ? contenido.description : contenido.descripcion }}
								</h4>
							</v-col>
						</v-row>
					</v-card-title>
				</v-card>
				<v-card>
					<v-card-title>
						<v-row>
							<v-col cols="12">
								<v-btn @click="addSeccion(1)" color="primary" block size="x-large">
									{{ $t('add') }} {{ $t('sections') }}
								</v-btn>
							</v-col>
							<template v-for="seccion in izquierda" :key="seccion.id">
								<v-col cols="12" md="6">
									<h2>
										{{ locale === 'en' ? seccion.title : seccion.titulo }}
									</h2>
								</v-col>
								<v-col cols="12" md="6">
									<v-btn color="primary" block size="x-large" @click="editarSeccion(seccion)">
										{{ $t('edit') }}
									</v-btn>
								</v-col>
								<v-col cols="12" align="left">
									<span class="text-pre-wrap">{{ locale === 'en' ? seccion.text : seccion.texto }}</span>
								</v-col>
								<v-col cols="12" v-if="seccion.imagenes.length > 0">
									<v-slide-group
										show-arrows
									>
										<v-slide-group-item
											v-for="image in seccion.imagenes"
											:key="image.id"
										>
											<v-card
												class="ma-4"
												height="100"
												width="100"
												@click="imagen=image.ruta;imagenDialog=true"
											>
												<div class="d-flex fill-height align-center justify-center">
													<v-scale-transition>
														<v-img :src="'/storage/'+image.ruta"/>
													</v-scale-transition>
												</div>
											</v-card>
										</v-slide-group-item>
									</v-slide-group>
								</v-col>
							</template>
						</v-row>
					</v-card-title>
				</v-card>
			</v-col>
			<v-col cols="12" md="5">
				<v-card v-if="!!contenido.imagen">
					<v-card-title>
						<v-img :src="'/storage/'+contenido.imagen"/>
					</v-card-title>
				</v-card>
				<v-card>
					<v-card-title>
						<v-row>
							<v-col cols="12">
								<v-btn @click="addSeccion(2)" color="primary" block size="x-large">
									{{ $t('add') }} {{ $t('sections') }} 
								</v-btn>
							</v-col>
							<template v-for="seccion in derecha" :key="seccion.id">
								<v-col cols="12" md="6">
									<h2>
										{{ locale === 'en' ? seccion.title : seccion.titulo }}
									</h2>
								</v-col>
								<v-col cols="12" md="6">
									<v-btn color="primary" block size="x-large" @click="editarSeccion(seccion)">
										{{ $t('edit') }}
									</v-btn>
								</v-col>
								<v-col cols="12" align="left">
									<span class="text-pre-wrap">{{ locale === 'en' ? seccion.text : seccion.texto }}</span>
								</v-col>
								<v-col cols="12" v-if="seccion.imagenes.length > 0">
									<v-slide-group
										show-arrows
									>
										<v-slide-group-item
											v-for="image in seccion.imagenes"
											:key="image.id"
										>
											<v-card
												class="ma-4"
												height="100"
												width="100"
												@click="imagen=image.ruta;imagenDialog=true"
											>
												<div class="d-flex fill-height align-center justify-center">
													<v-scale-transition>
														<v-img :src="'/storage/'+image.ruta"/>
													</v-scale-transition>
												</div>
											</v-card>
										</v-slide-group-item>
									</v-slide-group>
								</v-col>
							</template>
						</v-row>
					</v-card-title>
				</v-card>
			</v-col>
			<v-col cols="12">
				<v-card>
					<v-card-title>
						<v-row>
							<v-col cols="12">
								<v-btn @click="addSeccion(3)" color="primary" block size="x-large">
									{{ $t('add') }} {{ $t('sections') }} 
								</v-btn>
							</v-col>
							<template v-for="seccion in centro" :key="seccion.id">
								<v-col cols="12" md="6">
									<h2>
										{{ locale === 'en' ? seccion.title : seccion.titulo }}
									</h2>
								</v-col>
								<v-col cols="12" md="6">
									<v-btn color="primary" block size="x-large" @click="editarSeccion(seccion)">
										{{ $t('edit') }}
									</v-btn>
								</v-col>
								<v-col cols="12" align="left">
									<span class="text-pre-wrap">{{ locale === 'en' ? seccion.text : seccion.texto }}</span>
								</v-col>
								<v-col cols="12" v-if="seccion.imagenes.length > 0">
									<v-slide-group
										show-arrows
									>
										<v-slide-group-item
											v-for="image in seccion.imagenes"
											:key="image.id"
										>
											<v-card
												class="ma-4"
												height="100"
												width="100"
												@click="imagen=image.ruta;imagenDialog=true"
											>
												<div class="d-flex fill-height align-center justify-center">
													<v-scale-transition>
														<v-img :src="'/storage/'+image.ruta"/>
													</v-scale-transition>
												</div>
											</v-card>
										</v-slide-group-item>
									</v-slide-group>
								</v-col>
							</template>
						</v-row>
					</v-card-title>
				</v-card>
			</v-col>
		</v-row>
		<v-dialog persistent v-model="addSeccionDialog" max-width="900">
            <v-card>
                <v-card-title align="center" class="bg-primary">
                        {{ !selectedseccion.id ? $t('add') : $t('edit') }} sección
                        <v-btn icon="mdi-close" variant="text" @click="addSeccionDialog=false" />
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col cols="6" align="center">
                            <v-text-field
                                ref="titulo"
                                v-model="selectedseccion.titulo"
                                label="Titulo"
                                prepend-inner-icon="mdi-text-short"
                            />
                        </v-col>
						<v-col cols="6" align="center">
                            <v-text-field
                                ref="title"
                                v-model="selectedseccion.title"
                                label="Title"
                                prepend-inner-icon="mdi-text-short"
                            />
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-textarea
                                ref="texto"
                                v-model="selectedseccion.texto"
                                label="Texto"
                                prepend-inner-icon="mdi-text"
                            />
                        </v-col>
						<v-col cols="12" align="center">
                            <v-textarea
                                ref="text"
                                v-model="selectedseccion.text"
                                label="Text"
                                prepend-inner-icon="mdi-text"
                            />
                        </v-col>
                        <v-col cols="12" align="center">
                            <v-text-field
                                ref="orden"
                                v-model="selectedseccion.orden"
                                label="Orden"
                                type="number"
                                prepend-inner-icon="mdi-filter-variant"
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
                            <v-btn color="primary" @click="saveSeccion()" block size="x-large">
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
	</v-layout>
</template>
<script>
export default {
	props: ["contenido"],
	mounted() {
		this.getSecciones();
	},
	data() {
		return {
			loading: true,
			addSeccionDialog: false,
			imagenDialog: false,
			imagen: null,
			selectedseccion: {},
			posicion: null,
			errormsg: null,
			files: [],
			izquierda: [],
			derecha: [],
			centro: [],
		};
	},
	methods: 
	{
		getSecciones() {
			this.loading = true;
			axios.post("/get-secciones", {
				contenido_id: this.contenido.id
			}).then(({ data }) => {
                if (data.status == 200) {
                    this.izquierda = data.izquierda;
                    this.derecha = data.derecha;
                    this.centro = data.centro;
                }
                this.loading = false;
            });
		},
		addSeccion(val) {
			this.posicion = val;
			this.addSeccionDialog = true;
		},
		saveSeccion() {
            if (!this.loading) {
                this.loading = true;
                if (!this.selectedseccion.titulo) {
                    this.errormsg = $t('fillField')+"titulo.";
                    this.$refs.titulo.focus();
                    this.loading = false;
                } else if (!this.selectedseccion.texto) {
                    this.errormsg = $t('fillField')+"texto.";
                    this.$refs.texto.focus();
                    this.loading = false;
                } else if (!this.selectedseccion.orden) {
                    this.errormsg = $t('fillField')+"orden.";
                    this.$refs.orden.focus();
                    this.loading = false;
				} else {
                    let route = null;
                    let id = null;
					var formData = new FormData();
                    if (!this.selectedseccion.id) {
						route = "/save-seccion";
						formData.append('posicion', this.posicion);
                    } else {
                        route = "/edit-seccion";
                        id = this.selectedseccion.id;
						formData.append('posicion', this.selectedseccion.posicion);
					}
                    formData.append('id', id);
                    formData.append('titulo', this.selectedseccion.titulo);
                    formData.append('texto', this.selectedseccion.texto);
                    formData.append('orden', this.selectedseccion.orden);
                    formData.append('contenido_id', this.contenido.id);
                    if (!!this.files) {
                        this.files.forEach(file => {
							formData.append('files[]', file)
						})
                    }
					axios.post(route, formData).then(({ data }) => {
                        if (data.status == 200) {
                            this.$store.dispatch("snackbar/setSnackbar", {text: !this.selectedseccion.id ? "Seccion añadido correctamente." : "Seccion editado correctamente.", color:"success"});
							this.selectedseccion = {};
							this.files = [];
                            this.addSeccionDialog = false;
							this.getSecciones();
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
		editarSeccion(seccion) {
			this.selectedseccion = seccion;
			this.addSeccionDialog = true;
		},
		fileChanged (value) {
            this.files = value;
        },
	},
};
</script>