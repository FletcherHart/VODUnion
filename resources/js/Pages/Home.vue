<template>
    <header-layout>
        <section class="w-full h-full flex flex-col sm:pl-5 sm:pr-5 mb-10">
            <div class="w-full flex justify-center flex-col items-center">
                <h1 v-if="search" class="font-bold text-xl mb-6 mt-6">Search term: {{search}}</h1>
                <h1 v-else class="accessiblity-h1">Videos</h1>
                <h2 v-if="data.length <= 0" class="font-bold text-lg">Uh oh. There doesn't appear to be any videos to display.</h2>
            </div>
            <div class="overflow-hidden grid gap-3 sm:grid-cols-3 w-full sm:w-min xl:grid-cols-4 mt-5" v-bind:class="{ 
                'lg:grid-cols-4': this.$store.state.isHidden, 
                'xl:grid-cols-3 lg:grid-cols-3': !this.$store.state.isHidden
            }">
                <article v-for="video in data" :key="video.id">
                    <inertia-link class="flex p-2 h-full" :href="'video/'+video.id">
                        <div class="w-full">
                            <div class="flex flex-col h-full">
                                <div class="h-full flex">
                                    <div class="relative thumb">
                                        <img class="thumb" :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=169&width=300'" width="300px" height="169px" alt="" role="presentation">
                                        <div class="absolute bottom-2 text-white bg-black text-sm bg-opacity-50 ml-1">{{getTime(video.video_length)}}</div>
                                    </div>
                                </div>
                                <div class="flex justify-between h-1/6" :title="video.title">
                                    <h2 class="font-semibold truncate" >{{video.title}}</h2>
                                </div>
                                <div class="text-sm">
                                    <span>
                                    Views: {{video.views}} • {{getVideoAge(video.created_at)}}
                                    </span>
                                </div>
                                <div class="w-full h-full flex items-end">
                                    Uploaded by {{video.uploader}}
                                </div>
                            </div>
                        </div>
                    </inertia-link>
                </article>
            </div>
            <button class="mt-10 bg-blue-600 text-white rounded p-2 w-full" v-on:click="loadMoreVideos" v-if="this.data.length < this.maxVideos">Load More Videos</button>
        </section>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import {formatDistance, toDate} from 'date-fns'
    import { Inertia } from '@inertiajs/inertia'
    export default {
        components: {
            HeaderLayout
        },
        props: {
            data: Array,
            search: String,
            maxVideos: Number
        },
        methods: {
            getVideoAge(date) {
                return formatDistance(toDate(new Date(date)), new Date(), { addSuffix: true });
            },
            getTime(duration) {
                return new Date(duration * 1000).toISOString().substr(11, 8);
            },
            loadMoreVideos() {
                if((window.innerHeight + window.scrollY) >= document.body.scrollHeight && this.data.length < this.maxVideos)
                {
                    Inertia.post(route(route().current()), {num_videos: this.data.length}, {preserveScroll: true})
                }
            }
        }
    }
</script>
