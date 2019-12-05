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
                        <v-btn :loading="vkLoad" v-on:click="deleteGroup()" color="error">Удалить группу</v-btn>
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
                <th>История</th>
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
                    <v-icon v-if="student.vk_link !== '' && student.vk_link !== null"
                            v-on:click="showVkLink(student.id_student)" color="green">mdi-vk-box
                    </v-icon>
                    <v-icon v-if="student.vk_link === '' || student.vk_link === null"
                            v-on:click="showVkLink(student.id_student)" color="grey">mdi-vk-box
                    </v-icon>
                </td>
                <td>
                    <v-btn icon large>
                        <v-icon v-on:click="showDataStudent(student.id_student)">mdi-account-edit</v-icon>
                    </v-btn>
                </td>
                <td>
                    <router-link :to="{name: 'studentPosts', params:{idStudent: student.id_student}}">
                        <v-icon large>mdi-history</v-icon>
                    </router-link>
                </td>
            </tr>
            </tbody>
        </v-simple-table>
        <v-container v-if="!this.$parent.loading" class="fill-height">
            <v-row justify="center">
                <v-btn v-on:click="newStudentModal = true " color="success" outlined>
                    <v-icon>mdi-account-plus</v-icon>
                    Добавить студента
                </v-btn>
            </v-row>
        </v-container>
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
                                <v-text-field v-model="editStudent.phone" label="Номер телефона"
                                              required></v-text-field>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer>
                        <v-btn v-on:click="deleteStudent()" color="error">Удалить</v-btn>
                    </v-spacer>
                            <v-btn color="blue darken-1" text @click="studentEdit = false">Закрыть</v-btn>
                            <v-btn color="blue darken-1" :loading="this.load" v-on:click="save()" text>Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="vkEdit" persistent max-width="400px">
            <v-card :loading="this.load">
                <v-card-title>
                    <span class="headline">Профиль vk</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field v-model="vkLink" label="Ссылка на профиль VK" required></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-alert v-if="vkError !== null" dense outlined type="error">
                                    Профиль пользователя приватный
                                </v-alert>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="vkEdit = false">Закрыть</v-btn>
                    <v-btn color="blue darken-1" :loading="this.load" v-on:click="saveVkLink()" text>Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-dialog v-model="newStudentModal" persistent max-width="600px">
            <v-card :loading="this.load">
                <v-card-title>
                    <span class="headline"><v-icon>mdi-account-plus</v-icon> Добавить студента</span>
                </v-card-title>
                <v-card-text>
                    <v-container>
                        <v-form>
                                <v-text-field label="ФИО" v-model="newStudent.name" required></v-text-field>
                                <v-text-field label="Адресс" v-model="newStudent.address" required></v-text-field>
                                <v-text-field label="Телефон" v-model="newStudent.phone" required></v-text-field>
                        </v-form>
                    </v-container>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" text @click="newStudentModal = false">Закрыть</v-btn>
                    <v-btn color="blue darken-1" :loading="this.load" v-on:click="createStudent()" text>Сохранить</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </v-container>
</template>

<script>
    export default {
        name: "ShowComponent",
        data() {
            return {
                studentEdit: false,
                vkEdit: false,
                editStudent: {
                    id_student: null,
                    name: null,
                    phone: null,
                    name_group: {},
                    address: null,
                    vk_link: null,
                },
                newStudent: {
                    'id_student': null,
                    'name': null,
                    'group_id': null,
                    'phone': null,
                    'address': null
                },
                //флаги состояний
                vkError: null,
                load: false,
                vkLoad: false,
                vkLink: '',
                newStudentModal: false,
            }
        },
        methods: {
            createStudent(){
                this.load = true;
                this.newStudent.group_id = this.$parent.dataGroup.id_group;
              axios.post('student/create',this.newStudent)
                  .then(response => {
                    this.load = false;
                    this.newStudentModal = false;
                    this.newStudent.id_student = response.data.id_student;
                    this.$parent.showGroup(this.$parent.dataGroup.id_group);
              })
            },
            showDataStudent(studentId) {
                this.studentEdit = true;
                this.editStudent = this.$parent.students.find(function (i) {
                    return i.id_student === studentId;
                });
            },
            showVkLink(studentId) {
                this.vkError = null;
                this.vkEdit = true;
                this.editStudent = this.$parent.students.find(function (i) {
                    return i.id_student === studentId;
                });
                this.vkLink = this.editStudent.vk_link;
            },
            saveVkLink() {
                this.vkError = null;
                this.load = true;
                axios.post('/vk/saveLink', {
                    'vkLink': this.vkLink,
                    'studentId': this.editStudent.id_student
                }).then(() => {
                    this.vkEdit = false;
                    this.editStudent.vk_link = this.vkLink;
                    this.load = false;
                }).catch(() => {
                    this.load = false;
                    this.vkError = 'Профиль пользователя приватный';
                })
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
            },
            deleteGroup() {
                axios.post('group/delete',{
                    'idGroup': this.$parent.dataGroup.id_group
                }).then(() => {
                    this.$parent.statusShowing = false;
                    this.$parent.getGroups();
                })
            },
            deleteStudent() {
                this.load = true;
                axios.post('student/delete',{
                    id_student: this.editStudent.id_student
                }).then(() => {
                    this.load = false;
                    this.$parent.showGroup(this.$parent.dataGroup.id_group);
                    this.studentEdit = false;
                })
            }
        },
    }
</script>

<style scoped>

</style>
