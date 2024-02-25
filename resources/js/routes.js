import { createWebHistory, createRouter } from "vue-router";

import Home from "@/pages/Home.vue";
import CategoryPage from "@/pages/CategoryPage.vue";
import Dashboard from "@/components/Dashboard.vue";
import AdminBooks from "@/components/AdminBooks.vue";
import AdminWriters from "@/components/AdminWriters.vue";
import AdminCategories from "@/components/AdminCategories.vue";
import Login from "@/components/auth/Login.vue";
import Register from "@/components/auth/Register.vue";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Home,
    },
    {
        path: "/category/:slug",
        name: "CategoryPage",
        component: CategoryPage,
    },
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: {
            guestOnly: true
        }
    },
    {
        path: "/register",
        name: "Register",
        component: Register,
        meta: {
            guestOnly: true
        }
    },
    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
        meta: {
            authOnly: true
        }
    },
    {
        path: "/dashboard/books",
        name: "AdminBooks",
        component: AdminBooks,
        meta: {
            authOnly: true
        }
    },
    {
        path: "/dashboard/writers",
        name: "AdminWriters",
        component: AdminWriters,
        meta: {
            authOnly: true
        }
    },{
        path: "/dashboard/categories",
        name: "AdminCategories",
        component: AdminCategories,
        meta: {
            authOnly: true
        }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isLoggedIn = localStorage.getItem('token');

    if (to.matched.some(record => record.meta.authOnly)) {
        if (!isLoggedIn) {
            next({
                path: "/login",
            });
        } else {
            next();
        }
    } else if (to.matched.some(record => record.meta.guestOnly)) {
        if (isLoggedIn) {
            next({
                path: "/dashboard",
            });
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;
