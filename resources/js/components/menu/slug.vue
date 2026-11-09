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
								<span class="text-pre-wrap">{{ locale === 'en' ? contenido.description : contenido.descripcion }}</span>
							</v-col>
						</v-row>
					</v-card-title>
				</v-card>
				<v-card v-if="izquierda.length > 0">
					<v-card-title>
						<v-row>
							<template v-for="seccion in izquierda" :key="seccion.id">
								<v-col cols="12">
									<h2>
										{{ locale === 'en' ? seccion.title : seccion.titulo }}
									</h2>
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
			<v-progress-linear
				v-if="loading"
				color="primary"
				indeterminate
				label="Loading..."
			/>
			<v-col cols="12" md="5">
				<v-card v-if="!!contenido.imagen">
					<v-card-title>
						<v-img :src="'/storage/'+contenido.imagen"/>
					</v-card-title>
				</v-card>
				<v-card v-if="derecha.length > 0">
					<v-card-title>
						<v-row>
							<template v-for="seccion in derecha" :key="seccion.id">
								<v-col cols="12">
									<h2>
										{{ locale === 'en' ? seccion.title : seccion.titulo }}
									</h2>
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
			<v-col cols="12" v-if="centro.length > 0">
				<v-card>
					<v-card-title>
						<v-row>
							<template v-for="seccion in centro" :key="seccion.id">
								<v-col cols="12">
									<h2>
										{{ locale === 'en' ? seccion.title : seccion.titulo }}
									</h2>
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
import { useI18n } from 'vue-i18n';
export default {
	mounted() {
        this.slug = this.$route.params.slug;
		this.getContenido();
	},
	setup() {
		const { locale } = useI18n();
		return { locale };
	},
	data() {
		return {
			loading: true,
			slug: null,
			imagenDialog: false,
			imagen: null,
			contenido: {},
			izquierda: [],
			derecha: [],
			centro: [],
		};
	},
	methods: 
	{
		getContenido() {
			this.loading = true;
			axios.post("/get-contenido", {
				slug: this.slug
			}).then(({ data }) => {
                if (data.status == 200) {
                    this.contenido = data.contenido;
					document.title = this.locale === 'en' ? this.contenido.name : this.contenido.nombre;
					this.getSecciones();
                }
            });
		},
		getSecciones() {
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
	},
	watch: {
		'$route.params.slug'(nuevoSlug) {
			this.slug = nuevoSlug;
			this.getContenido();
		}
	},
};
</script>