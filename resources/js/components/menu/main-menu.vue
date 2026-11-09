<template>
    <v-layout>
        <v-container fluid>
            <v-row class="fill-height">
                <v-col cols="12" class="pa-4 pa-md-6">
                    <v-card 
                        min-height="400" 
                        class="d-flex flex-column text-white justify-center align-center" 
                        style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/images/bg.jpeg'); background-size: cover; background-position: center;"
                    >
                        <v-card-text class="text-center flex-grow-1 d-flex flex-column justify-center align-center px-4">
                            <img src="/images/text.png" class="img-fluid mb-4" style="max-width: 100%; width: 500px; height: auto;" :draggable="false" @contextmenu.prevent>
                            <p class="text-body-1">
                                {{ $t('identity') }}
                            </p>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12" md="6" class="pa-4 pa-md-6">
                    <v-card height="100%" class="d-flex flex-column">
                        <v-card-text class="text-center flex-grow-1">
                            <h1 class="text-h4 mb-4">Echoes of War</h1>
                            <p class="text-body-1">
                                {{ $t('description') }}
                            </p>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-6">
                            <v-btn variant="outlined" color="primary" size="large" href="https://drive.google.com/drive/folders/1Goq2iYRiXubwFffS_EqsLkgR1niyi3G8?usp=drive_link" target="_blank" rel="noopener noreferrer">
                                <v-icon start icon="mdi-gamepad-variant"></v-icon>
                                {{ $t('play') }}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
                <v-col cols="12" md="6" class="pa-4 pa-md-6">
                    <v-card height="100%" class="d-flex flex-column">
                        <v-card-text class="text-center flex-grow-1">
                            <h1 class="text-h4 mb-4">{{ $t('wiki') }}</h1>
                            <p class="text-body-1">
                                {{ $t('wikidata') }}
                            </p>
                        </v-card-text>
                        <v-card-actions class="justify-center pb-6">
                            <v-btn variant="outlined" color="primary" size="large" href="/storage/EchosOfWar.apk" download="EchosOfWar.apk">
                                <v-icon start icon="mdi-download"></v-icon>
                                {{ $t('download') }}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
                <v-col cols="12" class="pa-4 pa-md-6">
                    <v-card class="d-flex flex-column">
                        <v-card-text class="text-center p-0">
                            <div class="video-container" style="position: relative; width: 100%; padding-bottom: 56.25%; height: 0;">
                                <iframe 
                                    src="https://www.youtube.com/embed/7a5mHIZ9cOQ?si=iNFqN7tlGr0GDOQF" 
                                    title="YouTube video player" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                                    referrerpolicy="strict-origin-when-cross-origin" 
                                    allowfullscreen
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%;"
                                />
                            </div>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
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
