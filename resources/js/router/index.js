import { createWebHistory, createRouter } from "vue-router";
import Home from "@/Pages/Dashboard.vue";
import Reports from "@/Pages/Reports.vue";
import Cases from "@/Pages/Cases.vue";
import Maintenance from "@/Pages/Maintenance.vue";
import Archive from "@/Pages/Archive.vue";
import CasesSearch from "@/Pages/SearchResults.vue";
import CaseFunding from "@/Pages/CaseFunding.vue";

const routes = [
    {
        path: "/dashboard",
        name: "Home",
        component: Home,
    },
    {
        path: "/reports",
        name: "Reports",
        component: Reports,
    },
    {
        path: "/cases",
        name: "Cases",
        component: Cases
    },
    {
        path: "/cases-search",
        name: "CasesSearch",
        component: CasesSearch,
        props: true
    },
    {
        path: "/case-funding/{incident}",
        name: "CaseFunding",
        component: CaseFunding,
        props: true
    },
    {
        path: "/maintenance",
        name: "Maintenance",
        component: Maintenance,
    },
    {
        path: "/archive",
        name: "Archive",
        component: Archive,
    },

];
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
// const router = new VueRouter({
//     mode: 'history',
//     scrollBehavior (to, from, savedPosition) {
//         return { x: 0, y: 0 }
//     },
//     routes
// });
const router = createRouter({
    mode: 'history',
    history: createWebHistory(),
    routes,
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 }
    },
});

export default router;
