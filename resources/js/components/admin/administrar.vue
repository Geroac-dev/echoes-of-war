<template>
    <v-layout>
        <v-row>
            <v-col>
                <v-card class="elevation-0 pa-3">
                    <v-card-title>
                        <v-row>
                            <v-col cols="12">
                                <h1>
                                    Administrar
                                </h1>
                            </v-col>
                            <v-col cols="12">
                                <v-tabs
                                    v-model="tab"
                                    align-tabs="center"
                                    center-active
                                >
                                    <v-tab value="categorias">Categorias</v-tab>
                                    <v-tab value="personal">Personal</v-tab>
                                </v-tabs>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-tabs-window v-model="tab">
                            <v-tabs-window-item value="categorias">
                                <vc-categorias />
                            </v-tabs-window-item>
                            <v-tabs-window-item value="personal">
                                <vc-personal />
                            </v-tabs-window-item>
                        </v-tabs-window>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-layout>
</template>
<script>
export default {
    data() {
        return {
            tab: null,
        };
    },
};
</script>
  