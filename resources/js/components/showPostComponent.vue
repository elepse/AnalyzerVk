<template>
    <v-container>
        <v-btn color="primary" large text icon v-on:click="$parent.showPost = false">
            <v-icon>mdi-arrow-left-bold-circle-outline</v-icon>
        </v-btn>
        <v-card>
            <v-card-title>Вложения</v-card-title>
            <v-card-text>
                <v-row justify="center">
                    <v-col v-bind:key="key" v-if="post.attachments[0] !== undefined" v-for="(attach, key) in post.attachments" cols="6">
                            <v-img v-if="attach.type === 'photo' || attach.type === 'doc'" :src="attach.url"></v-img>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-card>
    </v-container>
</template>

<script>
    export default {
        name: "showPostComponent",
        props: {
            post: Object
        },
        data () {
            return {

            }
        },
    }
</script>

<style scoped>

</style>
