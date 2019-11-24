import VueRouter from 'vue-router'
// Pages
import AuthComponent from './components/AuthComponent'
import ExampleComponent from './components/ExampleComponent'
import TestComponent from './components/TestComponent'
import GroupComponent from './components/GroupsComponent'
// Routes
const routes = [
    {
        path: '/login',
        name: 'login',
        component: AuthComponent,
        meta: {
            auth: false
        },
    },
    {
        path: '/',
        name: 'home',
        component: ExampleComponent,
        meta: {
            auth: undefined
        },
    },
    {
        path: '/test',
        name: 'home',
        component: TestComponent,
        meta: {
            auth: true
        },
    },
    {
        path: '/groups',
        name: 'groups',
        component: GroupComponent,
        meta: {
            auth: true
        },
    }
];
const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
});
export default router