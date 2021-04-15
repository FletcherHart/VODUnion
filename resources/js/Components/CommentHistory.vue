<template>
    <section class="w-full">
        <article class="shadow border border-black m-2" v-for="comment in comments" :key="comment.id">
            <div class="my-3 ml-2 mr-2">
                  <!-- <a class="inline text-blue-600 hover:text-purple-600 mb-3" :href="'user/' + comment.user_id">{{comment.name}}</a> -->
                    <p class="break-words whitespace-pre-line whitespace-pre-wrap">{{comment.text}}</p>
                    <div class="flex justify-between mt-5">
                        <div class="self-end">
                            Commented {{getAge(comment.created_at)}} on <a class="underline text-blue-600" :href="route('video', comment.video_id)">{{comment.title}}</a>
                        </div>
                        <button class="bg-red-600 text-white rounded ml-2 mr-2 p-2"
                        v-on:click="deleteComment(comment.id)">Delete</button>
                    </div>
                  
                </div>
                <hr>
        </article>
        <div v-if="comments.length <= 0" class="flex items-center justify-center m-5">
            <h2 class="font-bold text-lg">You haven't made and comments yet.</h2>
        </div>
    </section>
</template>

<script>
    import {formatDistance, toDate} from 'date-fns'
    export default {
        props: {
            comments: Array
        },
        methods: {
            deleteComment(id) {
            this.$inertia.delete('/comment/'+id, {preserveScroll:true});
            },
            getAge(date) {
                return formatDistance(toDate(new Date(date)), new Date(), { addSuffix: true });
            },
        }
    }
</script>