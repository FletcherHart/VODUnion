<template>
    <header-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div v-if="search" class="w-full flex justify-center mb-6 mt-6">
                <h1 class="font-bold text-xl">Search term: {{search}}</h1>
            </div>
            <div class="overflow-hidden flex flex-col justify-items-center">
                <div class="thumb-container" v-for="video in data" :key="video.id">
                    <inertia-link class="flex mt-5 p-2 bg-white shadow rounded h-60" :href="'video/'+video.id">
                        <div class="w-full">
                            <div class="flex justify-between">
                                <h2 class="font-semibold">{{video.title}}</h2>
                                <div class="text-sm">
                                    <span>
                                    Views: {{video.views}} • {{getVideoAge(video.created_at)}}
                                    </span>
                                </div>
                            </div>
                            <hr>
                            <div class="flex h-4/6">
                                <div class="mr-5 h-full">
                                    <div class="relative thumb">
                                        <img :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=169&width=300'">
                                        <div class="absolute bottom-2 text-white bg-black text-sm bg-opacity-50 ml-1">{{getTime(video.video_length)}}</div>
                                    </div>
                                </div>
                                <div class="h-full">
                                    <div class="break-all">
                                        {{video.description}}
                                    </div>
                                </div>
                            </div>
                            <div class="h-1/6 flex items-end mt-3">
                                Uploaded by {{video.uploader}}
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
            },
            getTime(duration) {
                return new Date(duration * 1000).toISOString().substr(11, 8);
            }
        }
    }
</script>
