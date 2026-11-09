const CACHE_NAME = 'EchoesofWar';
const ARCHIVOS_INICIALES = [
    '/',
    '/app',
    '/offline.html',
    '/manifest.webmanifest',
    '/images/logo.png',
    '/images/ECHOS-OF-WAR.png',
];
// 1. Instalar y guardar los archivos iniciales
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
        .then((cache) => cache.addAll(ARCHIVOS_INICIALES))
    );
    self.skipWaiting();
});
// 2. Activar y eliminar cachés de versiones anteriores
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((nombres) => {
            return Promise.all(
                nombres
                .filter((nombre) => nombre !== CACHE_NAME)
                .map((nombre) => caches.delete(nombre))
            );
        })
    );
    self.clients.claim();
});
// 3. Interceptar las solicitudes
self.addEventListener('fetch', (event) => {
    if (event.request.method !== 'GET') {
        return;
    }
    const url = new URL(event.request.url);
    if (!url.protocol.startsWith('http')) {
        return; // Deja que el navegador maneje estas peticiones de forma nativa sin meterlas al caché
    }
    // Para las páginas: Internet primero y caché después
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
            .then((respuesta) => {
                const copia = respuesta.clone();

                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, copia);
                });
                return respuesta;
            })
            .catch(async () => {
                const paginaGuardada = await caches.match(event.request);
                return paginaGuardada || caches.match('/offline.html');
            })
        );
        return;
    }
    // Para CSS, JavaScript e imágenes: caché primero
    event.respondWith(
        fetch(event.request)
            .then((respuestaInternet) => {
                const copia = respuestaInternet.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(event.request, copia);
                });
                return respuestaInternet;
            })
            .catch(async () => {
                return await caches.match(event.request);
            })
    );
});