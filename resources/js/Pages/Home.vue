<template>
    <header-layout>
        <div class="w-full h-full flex sm:pl-5 sm:pr-5">
            <div v-if="search" class="w-full flex justify-center mb-6 mt-6">
                <h1 class="font-bold text-xl">Search term: {{search}}</h1>
            </div>
            <div class="overflow-hidden grid gap-3 sm:grid-cols-3 w-full sm:w-min xl:grid-cols-4" v-bind:class="{ 
                'lg:grid-cols-4': this.$store.state.isHidden, 
                'xl:grid-cols-3 lg:grid-cols-3': !this.$store.state.isHidden
            }">
                <div v-for="video in data" :key="video.id">
                    <inertia-link class="flex mt-5 p-2 bg-white shadow rounded h-full" :href="'video/'+video.id">
                        <div class="w-full">
                            <div class="flex flex-col">
                                <div class="h-full flex justify-center">
                                    <div class="relative thumb">
                                        <img :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=169&width=300'" width="300px" height="169px">
                                        <div class="absolute bottom-2 text-white bg-black text-sm bg-opacity-50 ml-1">{{getTime(video.video_length)}}</div>
                                    </div>
                                </div>
                                <div class="group relative flex justify-between h-1/6">
                                    <h2 class="font-semibold">{{titleSubstring(video.title)}}</h2>
                                    <p class="absolute top-0 z-50 group-hover:opacity-100 bg-gray-100 opacity-0 text-sm whitespace-nowrap">{{video.title}}</p>
                                </div>
                                <div class="text-sm">
                                    <span>
                                    Views: {{video.views}} • {{getVideoAge(video.created_at)}}
                                    </span>
                                </div>
                                <div class="flex h-full items-end mt-3">
                                    Uploaded by {{video.uploader}}
                                </div>
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
    import {formatDistance, toDate} from 'date-fns'
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
                return formatDistance(toDate(new Date(date)), new Date(), { addSuffix: true });
            },
            getTime(duration) {
                return new Date(duration * 1000).toISOString().substr(11, 8);
            },
            titleSubstring(title) {
                if(title.length > 35)
                    return title.substring(0,35) + "...";
                else    
                    return title;
            }
        }
    }
</script>
