<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Авторизация</div>

                    <div class="card-body">
                        <v-form @submit.prevent="login()"
                                ref="form"
                                lazy-validation
                        >
                            <v-text-field
                                    v-model="email"
                                    label="email"
                                    required
                            ></v-text-field>
                            <v-text-field
                                    v-model="password"
                                    :type="'password'"
                                    name="input-10-1"
                                    label="Пароль"
                            ></v-text-field>
                            <v-btn
                                    color="success"
                                    class="mr-4"
                                    type="submit"
                            >
                                Войти
                            </v-btn>
                        </v-form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                email: null,
                password: null,
                has_error: false,
            }
        },
        mounted() {
            //
        },
        methods: {
            login() {
                // get the redirect object
                var redirect = this.$auth.redirect();
                var app = this;
                this.$auth.login({
                    params: {
                        email: app.email,
                        password: app.password
                    },
                    success: function() {
                        // handle redirection
                        const redirectTo = redirect ? redirect.from.name : this.$auth.user().role === 2 ? 'home' : 'home';
                        this.$router.push({name: redirectTo});
                        this.download = false;
                    },
                    error: function() {
                        app.has_error = true;
                        this.download = false;
                    },
                    rememberMe: true,
                    fetchUser: true
                })
            }
        }
    }
</script>