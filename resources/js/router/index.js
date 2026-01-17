import { createRouter, createWebHistory } from "vue-router";
import Expenses from "../pages/Expenses.vue";

export default createRouter({
    history: createWebHistory("/"),
    routes: [
        { path: "/", redirect: "/expenses" },
        { path: "/expenses", component: Expenses },
    ],
});
