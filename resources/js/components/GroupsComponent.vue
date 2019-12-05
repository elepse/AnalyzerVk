<template>
    <v-container>
        <v-row justify="center" v-if="!this.statusShowing">
            <v-card min-width="300" v-on:click="$router.push('addGroup')" v-if="groups !== null" style="margin: 5px; border-color: #ff9800;" outlined height="150" hover>
                <v-container justify="center" class="fill-height">
                    <v-row style="margin: 0;" justify="center">
                        <v-icon large >mdi-account-multiple-plus-outline</v-icon>
                    </v-row>
                </v-container>
            </v-card>
            <v-card min-width="300" v-for="group in groups" v-bind:key="group.id_group" v-on:click="showGroup(group.id_group)" style="margin: 5px;" color="#FF9800" height="150" hover>
                <v-container class="fill-height">
                    <v-row style="margin: 0;" justify="center">
                        <h1 style="text-align: center; color: white">{{group.name_group}}</h1>
                    </v-row>
                </v-container>
            </v-card>
        </v-row>
        <show-component v-if="statusShowing"></show-component>
        <v-row v-if="loading" justify="center">
            <v-progress-circular
                :size="70"
                :width="7"
                color="orange"
                indeterminate
            ></v-progress-circular>
        </v-row>
    </v-container>
</template>
<script>
    import ShowComponent from "./ShowComponent";
    export default {
        name: "GroupsComponent",
        components: {ShowComponent},
        data() {
            return {
                groups: null,
                selectGroup: null,
                dataGroup: {
                    'name_group': '',
                    'name': '',
                    'year': ''
                },
                students: null,
                statusShowing: false,
                loading: false,
            }
        },
        methods: {
            getGroups() {
                this.groups = null;
                this.loading = true;
                axios.get('/getGroups')
                    .then(response => {
                        this.groups = response.data.groups;
                        this.loading = false;
                    });
            },
            showGroup(id) {
                this.students = null;
                this.loading = true;
                this.statusShowing = true;
                this.selectGroup = id;
                axios.get('/showGroup/' + id).then(response => {
                    this.loading = false;
                    this.dataGroup = response.data.group;
                    this.students = response.data.students;
                })
            }
        },
        created: function() {
            this.getGroups();
        }
    }
</script>

<style scoped>

</style>
