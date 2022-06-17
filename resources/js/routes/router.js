import Vue from "vue";
import Router from 'vue-router';
import Redirect from "../pages/Redirect";
import Main from "../pages/Main";

Vue.use(Router);

const router = new Router({
    mode: 'history', // to remove hash from URL
    routes: [
        { path: '/', name: 'Main', component: Main },
        { path: '/:hashCode', name: 'Redirect', component: Redirect },
        { path: '/*', name: 'Redirect', component: Redirect },
    ]
});

export default router;
