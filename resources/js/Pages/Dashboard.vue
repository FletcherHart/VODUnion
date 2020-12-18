<template>
    <header-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="overflow-hidden grid xl:grid-cols-4 md:grid-cols-3 sm:grid-cols-2 grid-cols-1 gap-3 justify-items-center">
                <div class="thumb-container" v-for="video in data" :key="video.id">
                    <inertia-link class="flex flex-col" :href="'video/'+video.id">
                        <div class="w-full bg-black flex justify-center ">
                            <img class="thumb" :src="'../storage/thumbnails/'+ video.storedAt + '.jpeg'">
                        </div>
                        {{video.title}}
                    </inertia-link>
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
            data: Array
        }
    }
</script>
