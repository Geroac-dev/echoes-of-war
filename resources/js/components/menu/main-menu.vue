```vue
<template>
	<v-layout>
		<v-row class="fill-height">
			<v-col cols="12" md="6" class="pa-6">
				<v-card height="100%" class="d-flex flex-column">
					<!-- <v-card-title class="justify-center">
						<img src="/storage/logo-juego.png" height="150">
					</v-card-title> -->
					<v-card-text class="text-center flex-grow-1">
						<h1 class="text-h4 mb-4">Echoes of War</h1>
						<p class="text-body-1">
							Adéntrate en un mundo lleno de peligros, enemigos y secretos.
							Descubre la historia y enfrenta los desafíos que te esperan.
						</p>
					</v-card-text>
					<v-card-actions class="justify-center pb-6">
						<v-btn color="primary" large href="/juego">
							<v-icon left>mdi-gamepad-variant</v-icon>
							Jugar ahora
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-col>
			<v-col cols="12" md="6" class="pa-6">
				<v-card height="100%" class="d-flex flex-column">
					<!-- <v-card-title class="justify-center">
						<img src="/storage/logo-wiki.png" height="150">
					</v-card-title> -->
					<v-card-text class="text-center flex-grow-1">
						<h1 class="text-h4 mb-4">Wiki de Echoes of War</h1>
						<p class="text-body-1">
							Consulta información sobre los personajes, enemigos, jefes,
							niveles, historia y todo lo que necesitas saber del juego.
						</p>
					</v-card-text>
					<v-card-actions class="justify-center pb-6">
						<v-btn color="primary" large class="mr-3" href="/wiki">
							<v-icon left>mdi-book-open-page-variant</v-icon>
							Ver Wiki
						</v-btn>
						<v-btn outlined color="primary" large href="/downloads/app.apk" download>
							<v-icon left>mdi-download</v-icon>
							Descargar App
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-col>
		</v-row>
	</v-layout>
</template>
<script>
export default {
	data() {
		return {
			loading: true,
		};
	},
};
</script>
```
