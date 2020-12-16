<template>
    <header-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="overflow-hidden grid grid-cols-4 gap-3">
                <div v-for="video in data" :key="video.id">
                    <inertia-link :href="'video/'+video.id">
                    <img :src="'../storage/thumbnails/'+ video.storedAt + '.jpeg'">
                    {{video.title}}
                    </inertia-link>
                    <hr>
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
