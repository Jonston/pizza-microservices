// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    compatibilityDate: '2025-07-15',
    devtools: {enabled: false},
    modules: [
        'nuxt-laravel-echo',
        '@nuxt/ui',
        '@pinia/nuxt'
    ],
    css: [
        '~/assets/css/main.css'
    ],
    echo: {
        key: '7sh28zpvaadjl8xsmlqe',
        broadcaster: 'reverb', // available: reverb, pusher
        host: 'localhost',
        port: 8080,
        scheme: 'http', // available: http, https
        transports: ['ws', 'wss'],
        authentication: {
            mode: 'cookie',
            baseUrl: 'http://localhost:80',
            authEndpoint: '/broadcasting/auth',
            csrfEndpoint: '/sanctum/csrf-cookie',
            csrfCookie: 'XSRF-TOKEN',
            csrfHeader: 'X-XSRF-TOKEN',
        },
        logLevel: 3,
        properties: undefined,
    },
    vite: {
        optimizeDeps: {
            include: ['pusher-js'],
        },
    },
    devServer: {
        host: '0.0.0.0',
        port: 3000
    },
})