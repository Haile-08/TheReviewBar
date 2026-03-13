import { createApp, h, DefineComponent } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob<DefineComponent>("./Pages/**/*.vue", { eager: true });
        const page = pages[`./Pages/${name}.vue`];
        
        return page;
    },
    setup({ el, App, props, plugin }) {
        createApp({
            render: () => h(App, props) 
        })
            .use(plugin)
            .mount(el);
    },
});