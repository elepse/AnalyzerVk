<template>
    <v-container>
        <div v-if="!showPost">
            <v-btn color="primary" large text icon v-on:click="$router.back()">
                <v-icon>mdi-arrow-left-bold-circle-outline</v-icon>
            </v-btn>
            <v-row v-if="!load" justify="center">
                <template v-for="(post, key) in posts">
                    <v-hover
                        v-slot:default="{ hover}">
                        <v-card min-width="300px" hover style="margin: 8px;" class="mx-auto" max-width="380px">
                            <div v-if="post.attachments[0] !== undefined">
                                <v-img
                                    v-if="post.attachments[0].type === 'photo' || post.attachments[0].type === 'doc'"
                                    class="white--text align-end" height="400px"
                                    :src="post.attachments[0].url">
                                </v-img>
                            </div>
                            <v-card-subtitle class="pb-0">{{(new Date(post.date * 1000)).toLocaleString("ru")}}</v-card-subtitle>
                            <v-card-text class="text--primary">
                                <div>{{post.text}}</div>
                                <div v-if="post.attachments[0] !== undefined" v-for="attach in post.attachments">
                                    <p v-if="attach.type === 'audio'"><v-icon>mdi-file-music</v-icon> {{attach.title}}</p>
                                </div>
                            </v-card-text>
                            <v-fade-transition>
                                <v-overlay
                                    v-if="hover"
                                    absolute
                                    color="#036358"
                                >
                                    <v-btn v-on:click="showMore(post)">Открыть</v-btn>
                                </v-overlay>
                            </v-fade-transition>
                        </v-card>
                    </v-hover>
                </template>
            </v-row>
            <v-row v-if="load" justify="center">
                <v-progress-circular
                    :size="70"
                    :width="7"
                    color="orange"
                    indeterminate
                ></v-progress-circular>
            </v-row>
        </div>
        <show-post-component v-if="showPost" :post="post"></show-post-component>
    </v-container>
</template>
<script>
    import ShowPostComponent from "./showPostComponent";

    export default {
        components: {ShowPostComponent},
        props: {
            idStudent: Number,
        },
        name: "studentPostsComponent",
        data() {
            return {
                load: false,
                posts: null,
                overlay: false,
                post: null,
                showPost: false,
            }
        },
        methods: {
            getPosts() {
                this.load = true;
                axios.get('student/getPosts/' + this.idStudent)
                    .then(response => {
                        this.posts = response.data.posts;
                        this.load = false;
                    })
            },
            showMore(post) {
                this.post = post;
                this.showPost = true;
            },
        },
        created() {
            this.getPosts();
        }
    }
</script>

<style scoped>

</style>
