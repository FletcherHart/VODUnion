<template>
    <header-layout>
        <div class="h-auto -mt-4">
            <div class="h-screen">
                <iframe class="h-3/4 w-full" :src="'https://iframe.videodelivery.net/'+ data.videoID +'?autoplay=true'" allow="accelerometer; gyroscope; autoplay; encrypted-media;" allowfullscreen="true"></iframe>
                <div class="mt-3 flex justify-center">
                    <div class="w-11/12">
                        <h2>{{data.title}}</h2>
                        <div class="flex items-center justify-between">
                            <p>Views: {{data.views}}</p>
                            <div class="flex">
                                <button v-on:click="like" class="flex focus:outline-none m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6" viewBox="0 0 8 8" v-bind:class="{'fill-current text-green-600':(isLiked === 1)}">
                                        <path d="M4.47 0c-.19.02-.37.15-.47.34-.13.26-1.09 2.19-1.28 2.38-.19.19-.44.28-.72.28v4h3.5c.21 0 .39-.13.47-.31 0 0 1.03-2.91 1.03-3.19 0-.28-.22-.5-.5-.5h-1.5c-.28 0-.5-.25-.5-.5s.39-1.58.47-1.84c.08-.26-.05-.54-.31-.63-.07-.02-.12-.04-.19-.03zm-4.47 3v4h1v-4h-1z"/>
                                    </svg>
                                    {{likes}}
                                </button> 
                                <button v-on:click="dislike" class="flex focus:outline-none m-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6" viewBox="0 0 8 8" v-bind:class="{'fill-current text-red-600':(isLiked === 0)}">
                                        <path d="M0 0v4h1v-4h-1zm2 0v4c.28 0 .53.09.72.28.19.19 1.15 2.12 1.28 2.38.13.26.39.39.66.31.26-.08.4-.36.31-.63-.08-.26-.47-1.59-.47-1.84s.22-.5.5-.5h1.5c.28 0 .5-.22.5-.5s-1.03-3.19-1.03-3.19c-.08-.18-.26-.31-.47-.31h-3.5z" />
                                    </svg>
                                    {{dislikes}}
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div>
                            <div class="cursor-pointer" @click="toggleDesc">Show All</div>
                            <div class="break-words" v-bind:class="{'truncate': hideDescription, 'h-auto min-h-full': !hideDescription}">{{data.description}}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <comment-layout :id="data.id" :ownerID="data.user_id" :totalComments="totalComments"></comment-layout>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import CommentLayout from '@/Layouts/CommentLayout'
    export default {
        components: {
            HeaderLayout,
            CommentLayout
        },
        props: {
            data: Object,
            comments: Array,
            totalComments: Number,
            isLiked: Number,
            likes: Number,
            dislikes: Number
        },
        data() {
            return{
                hideDescription: true
            }
        },
        methods: {
            toggleDesc() {
                this.hideDescription = !this.hideDescription
            },
            like() {
                this.$inertia.post('/video/'+this.data.id+'/like', {preserveScroll:true});
            },
            dislike() {
                this.$inertia.post('/video/'+this.data.id+'/dislike', {preserveScroll:true});
            },
        },
        mounted() {
            this.$store.commit('setHidden');
        }
    }
</script>
