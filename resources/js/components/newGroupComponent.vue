<template>
    <v-container>
        <v-card :loading="load">
            <v-card-title>Добавление новой группы</v-card-title>
            <v-card-text>
                <v-form>
                    <v-text-field label="Имя группы" v-model="newGroup.name_group" required></v-text-field>
                    <v-select label="Куратор" v-model="newGroup.curator" :items="curators"></v-select>
                    <v-text-field label="Год" v-model="newGroup.year" required></v-text-field>
                </v-form>
            </v-card-text>
        </v-card>
        <v-row style="margin: 0;" justify="center">
            <v-col cols="12">
                <v-btn color="success" :loading="load" v-on:click="createGroup()">
                    Создать
                </v-btn>
                <v-btn color="primary" :disabled="load" outlined v-on:click="$router.push('groups')">
                    Назад
                </v-btn>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "newGroupComponent",
        data() {
            return {
                newGroup: {
                    'name_group': null,
                    'curator': null,
                    'year': null,
                },
                load: false,
                curators: [],
            }
        },
        methods: {
            getCurators() {
                this.load = true;
                axios.get('group/getCurators')
                    .then(response => {
                        this.curators = null;
                        let items = [];
                        response.data.curators.forEach(i => {
                            let item = {
                                text: i.name,
                                value: i.id,
                            };
                            items.push(item);
                        });
                        this.curators = items;
                        this.load = false;
                    });
            },
            createGroup() {
                this.load = true;
                axios.post('group/create', this.newGroup)
                .then(() =>{
                    this.load = false;
                    this.$router.push('groups');
                })
                .catch(() =>{
                    this.load = false;
                })
            }
        }, created() {
            this.getCurators();
        }
    }
</script>

<style scoped>

</style>
