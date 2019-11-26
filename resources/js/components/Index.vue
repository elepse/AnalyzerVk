<template>
    <v-app id="inspire">
        <v-navigation-drawer
                v-model="drawer"
                app
        >
            <navbar-component></navbar-component>
        </v-navigation-drawer>
        <v-app-bar
                app
                color="indigo"
                dark
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"/>
            <v-toolbar-title>AnalyzerVk</v-toolbar-title>
        </v-app-bar>
        <v-content>
                    <router-view></router-view>
        </v-content>
    </v-app>
</template>

<script>
    import NavbarComponent from "./NavbarComponent";
    export default {
        components: {NavbarComponent},
        props: {
            source: String,
        },
        data: () => ({
            drawer: true,
            state: null,
            authStatus: false,
        }),
        created() {
            this.state = this.$store.state;
        },
        methods: {

        }
    }
</script>
