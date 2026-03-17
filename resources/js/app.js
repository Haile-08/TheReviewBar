import './bootstrap';

import { createApp, h } from "vue";
import { createInertiaApp, Link, Head } from "@inertiajs/vue3";
import { ZiggyVue } from 'ziggy-js';

createInertiaApp({
    title:  (title) => `THE REVIEW BAR | ${title}`,
    resolve: (name) => {
        const pages = import.meta.glob("./Pages/**/*.vue", { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(App, props) 
        })
            .use(plugin)
            .use(ZiggyVue)
            .component("Head", Head)
            .component("Link", Link)
            .mount(el);
    },
    progress: {
        color: '#009966',
    }
});