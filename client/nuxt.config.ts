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
        broadcaster: 'reverb',
        host: 'localhost',
        port: 8080,
        scheme: 'http',
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
        server: {
            hmr: {
                port: 24678,
                host: '0.0.0.0'
            },
            watch: {
                usePolling: true
            }
        },
        optimizeDeps: {
            include: ['pusher-js'],
        },
    },
    devServer: {
        host: '0.0.0.0',
        port: 3000
    },
    runtimeConfig: {
        ordersBaseUrl: process.env.ORDERS_CONTAINER_BASE_URL,
        catalogBaseUrl: process.env.CATALOG_CONTAINER_BASE_URL,
        notificationBaseUrl: process.env.NOTIFICATION_CONTAINER_BASE_URL,

        public: {
            ordersBaseUrl: process.env.ORDERS_PUBLIC_BASE_URL,
            catalogBaseUrl: process.env.CATALOG_PUBLIC_BASE_URL,
            notificationBaseUrl: process.env.NOTIFICATION_PUBLIC_BASE_URL,
        }
    }
})