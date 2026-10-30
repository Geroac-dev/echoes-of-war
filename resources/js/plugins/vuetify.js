import { createVuetify } from 'vuetify';
import 'vuetify/styles';
import { es,en } from 'vuetify/locale';
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";

const vuetify = createVuetify({
      components: {
        ...components,
    },
    directives,
    locale: {
        locale: 'es',
        messages: { es,en },
    },
    icons: {
        iconfont: 'mdi',
    },
    theme: {
        defaultTheme: 'light',
        themes: {
            light: {
                dark: false,
                colors: {
                    primary: '#000000',
                    background: '#EFF3F8',
                    error: '#D0094E',
                    info: '#49C4CC',
                    success: '#27AE60',
                    warning: '#F2C94C',
                }
            },
            dark: {
                dark: true,
                colors: {
                    primary: '#e9e9e9ff',
                    background: '#3d3d3dff',
                    error: '#8F1320',
                    info: '#337B80',
                    success: '#247D49',
                    warning: '#A68D41',
                }
            }
        }
    },
    display: {
        mobileBreakpoint: 'sm',
        thresholds: {
          xs: 0,
          sm: 340,
          md: 540,
          lg: 800,
          xl: 1280,
        },
      },
});

export default vuetify;