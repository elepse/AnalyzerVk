<template>
    <v-container>
        <v-btn color="primary" large text icon v-on:click="$parent.statusShowing = false">
            <v-icon>mdi-arrow-left-bold-circle-outline</v-icon>
        </v-btn>
        <v-card :loading="this.$parent.loading">
            <v-card-title>
                Данные группы
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col justify="center" cols="4">
                        <v-text-field outlined v-model="this.$parent.dataGroup.name_group" label="Имя группы"
                                      disabled></v-text-field>
                    </v-col>
                    <v-col justify="center" cols="4">
                        <v-text-field outlined v-model="this.$parent.dataGroup.name" label="Куратор"
                                      disabled></v-text-field>
                    </v-col>
                    <v-col justify="center" cols="4">
                        <v-text-field outlined disabled v-model="this.$parent.dataGroup.year" label="Год зачисления">4
                        </v-text-field>
                    </v-col>
                    <v-col justify="center" cols="12">
                        <v-btn :loading="vkLoad" v-on:click="collectVkData()" color="info">Загрузить VK данные</v-btn>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
        <br>
        <v-simple-table :loading="$parent.loading">
            <thead>
            <tr>
                <th>#</th>
                <th>ФИО</th>
                <th>Номер телефона</th>
                <th>Группа</th>
                <th>Адресс</th>
                <th>Соц.сети</th>
                <th>Ред.</th>
            </tr>
            </thead>
            <tbody>
            <tr v-bind:key="student.id_student" v-for="(student, key) in this.$parent.students">
                <td>{{key++}}</td>
                <td>{{student.name}}</td>
                <td>{{student.phone}}</td>
                <td>{{$parent.dataGroup.name_group}}</td>
                <td>{{student.address}}</td>
                <td>
                    <v-icon v-if="student.vk_link !== null" color="green">mdi-vk-box</v-icon>
                </td>
                <td>
                    <v-btn icon large>
                        <v-icon v-on:click="showDataStudent(student.id_student)">mdi-account-edit</v-icon>
                    </v-btn>
                </td>
            </tr>
            </tbody>
        </v-simple-table>
        <v-dialog v-model="studentEdit" persistent max-width="600px">
            <v-card :loading="this.load">
                <v-card-title>
                    <span class="headline">Профиль студента</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field v-model="editStudent.name" label="ФИО" required></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field v-model="editStudent.address" label="Адресс" required></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field v-model="editStudent.vk_link" label="Ссылка на профиль vk"
                                              required></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field v-model="editStudent.name_group" label="Группа" required></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field v-model="editStudent.phone" label="Номер телефона"
                                              required></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="studentEdit = false">Закрыть</v-btn>
                    <v-btn color="blue darken-1" :loading="this.load" v-on:click="save()" text>Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-row v-if="$parent.loading" justify="center">
            <v-btn x-large loading icon text></v-btn>
        </v-row>
    </v-container>
</template>

<script>
    export default {
        name: "ShowComponent",
        data() {
            return {
                studentEdit: false,
                editStudent: {
                    id_student: null,
                    name: null,
                    phone: null,
                    name_group: {},
                    address: null,
                    vk_link: null,
                },
                load: false,
                vkLoad: false
            }
        },
        methods: {
            showDataStudent(studentId) {
                this.studentEdit = true;
                this.editStudent = this.$parent.students.find(function (i) {
                    return i.id_student === studentId;
                });
            },
            save() {
                this.load = true;
                axios.post('/student/save', {
                    id_student: this.editStudent.id_student,
                    flo: this.editStudent.name,
                    phone: this.editStudent.phone,
                    address: this.editStudent.address,
                    vkLink: this.editStudent.vk_link
                }).then(() => {
                    this.studentEdit = false;
                    this.load = false;
                })
            },
            collectVkData() {
                this.vkLoad = true;
                axios.get('/vk/collectData/' + this.$parent.dataGroup.id_group)
                    .then(response => {
                        this.vkLoad = false;
                        console.log(response);
                    })
            }
        },
    }
</script>

<style scoped>

</style>