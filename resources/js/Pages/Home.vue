<template>
    <header-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div v-if="search" class="w-full flex justify-center mb-6 mt-6">
                <h1 class="font-bold text-xl">Search term: {{search}}</h1>
            </div>
            <div class="overflow-hidden flex flex-col justify-items-center">
                <div class="thumb-container" v-for="video in data" :key="video.id">
                    <inertia-link class="flex mt-5 p-2 bg-white shadow rounded" :href="'video/'+video.id">
                        <div class="bg-black flex justify-center mr-5">
                            <img :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=169&width=300'">
                        </div>
                        <div class="w-full relative">
                            <div class="flex justify-between">
                                <h2 class="font-semibold">{{video.title}}</h2>
                                <div class="text-sm">
                                    Views: {{video.views}}
                                    •
                                    {{getVideoAge(video.created_at)}}
                                </div>
                            </div>
                            <hr>
                            <div>
                                {{video.description}}
                            </div>
                            <div class="absolute bottom-0">
                                {{video.uploader}}
                            </div>
                        </div>
                    </inertia-link>
                </div>
            </div>
        </div>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import moment from 'moment'
    export default {
        components: {
            HeaderLayout
        },
        props: {
            data: Array,
            search: String
        },
        methods: {
            getVideoAge(date) {
                return moment([date]).fromNow();
            }
        }
    }
</script>
