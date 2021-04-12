<template>
    <header-layout>
        <section class="flex flex-col mb-10">
            <article v-for="video in videos" :key="video.id" class="mt-3 ml-3">
                <inertia-link class="flex p-2 h-full" :href="'video/'+video.id">
                    <div class="w-full">
                        <div class="flex flex-row h-full">
                            <div class="h-full flex mr-3">
                                <div class="relative thumb">
                                    <img class="thumb" :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=169&width=300'" width="300px" height="169px" alt="" role="presentation">
                                    <div class="absolute bottom-2 text-white bg-black text-sm bg-opacity-50 ml-1">{{getTime(video.video_length)}}</div>
                                </div>
                            </div>
                            <div class="flex flex-col" :title="video.title">
                                <h2 class="font-semibold" >{{video.title}}</h2>
                                <div>
                                    <div class="text-sm">
                                        <span>
                                        Views: {{video.views}} • Uploaded by {{video.uploader}} {{getVideoAge(video.created_at)}} 
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </inertia-link>
            </article>
            <button class="mt-10 bg-blue-600 text-white rounded float-right" v-on:click="loadMoreVideos" v-if="this.videos.length < this.maxVideos">Load More Videos</button>
        </section>
        <div v-if="videos.length <= 0" class="flex items-center justify-center m-5">
            <h1 class="font-bold text-lg">You haven't watched any videos yet.</h1>
        </div>
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
      videos: Array,
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
                if((window.innerHeight + window.scrollY) >= document.body.scrollHeight && this.videos.length < this.maxVideos)
                {
                    Inertia.post(route('history'), {num_videos: this.videos.length}, {preserveScroll: true})
                }
            }
    }
}
</script>