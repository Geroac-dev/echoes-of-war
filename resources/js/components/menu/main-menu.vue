```vue
<template>
	<v-layout>
		<v-row class="fill-height">
			<v-col cols="12" md="6" class="pa-6">
				<v-card height="100%" class="d-flex flex-column">
					<v-card-text class="text-center flex-grow-1">
						<h1 class="text-h4 mb-4">Echoes of War</h1>
						<p class="text-body-1">
							{{ $t('description') }}
						</p>
					</v-card-text>
					<v-card-actions class="justify-center pb-6">
						<v-btn color="primary" large href="/juego">
							<v-icon left>mdi-gamepad-variant</v-icon>
							{{ $t('play') }}
						</v-btn>
					</v-card-actions>
				</v-card>
			</v-col>
			<v-col cols="12" md="6" class="pa-6">
				<v-card height="100%" class="d-flex flex-column">
					<v-card-text class="text-center flex-grow-1">
						<h1 class="text-h4 mb-4">{{ $t('wiki') }}</h1>
						<p class="text-body-1">
							{{ $t('wikidata') }}
						</p>
					</v-card-text>
					<v-card-actions class="justify-center pb-6">
						<v-btn outlined color="primary" large href="/storage/EchosOfWar.apk" download="EchosOfWar.apk">
							<v-icon left>mdi-download</v-icon>
							{{ $t('download') }}
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
