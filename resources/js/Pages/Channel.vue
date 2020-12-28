<template>
    <header-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="overflow-hidden grid grid-cols-1 gap-4">
                <div class="w-full flex flex-row justify-between border-2 border-black" v-for="video in data" :key="video.id">
                    <div class="w-40">
                        <inertia-link class="flex flex-col" :href="'video/'+video.id">
                            <div class="w-full bg-black flex justify-center ">
                                <img :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=270'">
                            </div>
                            {{video.title}} 
                        </inertia-link>
                    </div>
                    <form @submit.prevent="submit" class="grid grid-cols-1">
                        <input class="border-solid border-2 border-black-600" id="title" name="title" placeholder="Video Title" >
                        <div v-if="errors.title"><mark>{{ errors.title }}</mark></div>

                        <input class="border-solid border-2 border-black-600" id="description" name="description" rows=5 placeholder="Description">
                        <div v-if="errors.description"><mark>{{ errors.description }}</mark></div>

                        <span>
                            <label for="video">Video: </label>
                            <input type="file" id="video" name="video" ref="video" accept=".mp4">
                        </span>
                        <div v-if="errors.video"><mark>{{ errors.video }}</mark></div>

                        <span>
                            <label for="thumb">Thumbnail: </label>
                            <input type="file" id="thumb" name="thumb" ref="thumb" accept=".jpeg, .jpg, .png">
                        </span>
                        <div v-if="errors.thumb"><mark>{{ errors.thumb }}</mark></div>

                        <span>
                            <label for="listed">List video immediatly upon upload?</label>
                            <input type="checkbox" name="listed" id="listed">
                        </span>
                        <div v-if="errors.listed"><mark>{{ errors.listed }}</mark></div>

                        <span>
                            <label for="raw">Maintain raw video quality?</label>
                            <input type="checkbox" name="raw" id="raw">
                        </span>

                        <button type="submit" class="bg-blue-600">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    export default {
        components: {
            HeaderLayout
        },
        props: {
            data: Array,
            errors: Object
        }
    }
</script>
