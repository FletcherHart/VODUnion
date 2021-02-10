<template>
    <header-layout>
        <div class="h-auto -mt-4">
            <div class="h-screen">
                <iframe class="h-3/4 w-full" :src="'https://iframe.videodelivery.net/'+ data.videoID +'?autoplay=true'" allow="accelerometer; gyroscope; autoplay; encrypted-media;" allowfullscreen="true"></iframe>
                <div class="mt-3 flex justify-center">
                <div class="w-11/12">
                    <div class="flex items-center justify-between">
                        <h2>{{data.title}}</h2>
                        <p>Views: {{data.views}}</p>
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
        
        <comment-layout></comment-layout>
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
            comments: Array
        },
        data() {
            return{
                hideDescription: true
            }
        },
        methods: {
            toggleDesc() {
                this.hideDescription = !this.hideDescription
            }
        },
        mounted() {
            this.$store.commit('setHidden');
        }
    }
</script>
