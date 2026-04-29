import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'e-Rapor SD Modern';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin);
        
        // Define route helper with safety fallback
        const routeHelper = typeof route !== 'undefined' ? route : (name) => {
            console.warn(`Ziggy route helper missing for: ${name}`);
            return '#';
        };
        
        vueApp.mixin({ methods: { route: routeHelper } });
        vueApp.config.globalProperties.route = routeHelper;
        
        return vueApp.mount(el);
    },
    progress: {
        color: '#3b82f6',
    },
});
