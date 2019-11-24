<template>
    <v-list dense>
        <!--Не авторизованный пользователь-->
        <v-list-item v-if="!$auth.check()" v-for="(route, key) in routes.unlogged" v-bind:key="route.path" link>
            <v-list-item-action>
                <v-icon>{{route.icon}}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <router-link :to="{ name : route.path }" :key="key">
                    {{route.name}}
                </router-link>
            </v-list-item-content>
        </v-list-item>
        <!--Авторизованный пользователь-->
        <v-list-item v-if="$auth.check(1)" v-for="(route, key) in routes.user" v-bind:key="route.path" link>
            <v-list-item-action>
                <v-icon>{{route.icon}}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <router-link :to="{ name : route.path }" :key="key">
                    {{route.name}}
                </router-link>
            </v-list-item-content>
        </v-list-item>
        <!--Авторизованный Админ-->
        <v-list-item v-if="$auth.check(2)" v-for="(route, key) in routes.user" v-bind:key="route.path" link>
            <v-list-item-action>
                <v-icon>{{route.icon}}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <router-link :to="{ name : route.path }" :key="key">
                    {{route.name}}
                </router-link>
            </v-list-item-content>
        </v-list-item>
        <v-list-item v-if="$auth.check()" link>
            <v-list-item-action>
                <v-icon>mdi-exit-run</v-icon>
            </v-list-item-action>
            <v-list-item-content>
                <a @click.prevent="$auth.logout()">Выйти</a>
            </v-list-item-content>
        </v-list-item>
<!--        <v-list-item v-if="$auth.check()">-->
<!--            <v-list-item-action>-->
<!--                <v-icon>mdi-account</v-icon>-->
<!--            </v-list-item-action>-->
<!--            <v-list-item-content>-->
<!--                {{userName}}-->
<!--            </v-list-item-content>-->
<!--        </v-list-item>-->
    </v-list>
</template>

<script>
    export default {
        name: "NavbarComponent",

        data() {
            return {
                routes: {
                    // UNLOGGED
                    unlogged: [
                        {
                            name: 'Вход',
                            icon: "mdi-contact-mail",
                            path: 'login'
                        },
                        {
                            name: 'Главная',
                            icon: 'mdi-home',
                            path: 'home'
                        },
                    ],
                    // LOGGED USER
                    user: [
                        {
                            name: 'Главная',
                            icon: 'mdi-home',
                            path: 'home'
                        },
                    ],
                    // LOGGED ADMIN
                    admin: [
                        {
                            name: 'Главная',
                            icon: 'mdi-home',
                            path: '/'
                        },
                    ]
                },
                userName: null
            }
        },
        methods: {
            getUserName() {
                axios.get('/auth/user')
                    .then(response => {
                        this.user = response.data.name;
                        console.log(response);
                    });
            }
        },
    }
</script>

<style scoped>

</style>