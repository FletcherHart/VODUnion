<template>
    <header-layout>
        <section class="flex flex-col mb-10 justify-center items-center">
            <h1 v-if="comments != null" class="font-bold text-xl">Comment History</h1>
            <h1 v-if="videos != null" class="font-bold text-xl">Watched Videos History</h1>
            <div class="flex justify-center mt-5">
                <inertia-link :href="route('history')" class="bg-blue-600 text-white rounded p-2 m-1">Video History</inertia-link>
                <inertia-link :href="route('comments')" class="bg-blue-600 text-white rounded p-2 m-1">Comment History</inertia-link>
            </div>
            <comment-history v-if="comments != null" :comments="comments"></comment-history>
            <video-history-component v-if="videos != null" :videos="videos" :maxVideos="maxVideos"></video-history-component>
        </section>
    </header-layout>
</template>

<script>
import HeaderLayout from '@/Layouts/HeaderLayout'
import CommentHistory from '../Components/CommentHistory.vue'
import VideoHistoryComponent from '../Components/VideoHistoryComponent.vue'
export default {
  components: {
      HeaderLayout,
      CommentHistory,
      VideoHistoryComponent
  },
  props: {
      videos: Array,
      maxVideos: Number,
      comments: Array
  },
    methods: {
        
    }
}
</script>