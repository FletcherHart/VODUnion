<template>
    <header-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="overflow-hidden flex flex-col justify-center">
                <div class="w-full grid grid-cols-3 gap-4 border-2 border-black" v-for="video in data" :key="video.id">
                    <div class="w-40 sm:w-auto">
                        <div class="w-full bg-black flex justify-center ">
                            <img v-if="!video.thumbnail" :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=270'">
                            <img v-else :src="'/storage/thumbnails/' + video.thumbnail">
                        </div>
                    </div>
                    <form @submit.prevent="submit(video.id)" class="grid grid-cols-1 col-span-2" :id="video.id">
                        <span>
                            <label for="title">Title: </label>
                            <input class="border-solid border-2 border-black-600" id="title" name="title" placeholder="Video Title" :value="video.title">
                        </span>
                        <!-- <div v-if="errors.title"><mark>{{ errors.title }}</mark></div> -->

                        <span class="w-full">
                            <label for="description">Description:</label>
                            <textarea class="border-solid border-2 border-black-600 resize-none" id="description" name="description" rows=5 placeholder="Description" :value="video.description"/>
                        </span>
                        <!-- <div v-if="errors.description"><mark>{{ errors.description }}</mark></div> -->

                        <span>
                            <label for="thumbnail">Thumbnail: </label>
                            <input type="file" id="thumbnail" name="thumbnail" ref="thumbnail" accept=".jpeg, .jpg, .png">
                        </span>
                        <!-- <div v-if="errors.thumb"><mark>{{ errors.thumb }}</mark></div> -->

                        <span>
                            <label for="list">Visibile:</label>
                            <input v-if="video.listed == 0" type="checkbox" name="list" id="list">
                            <input v-else type="checkbox" name="list" id="list" checked>
                        </span>
                        <div v-if="errors.list"><mark>{{ errors.list }}</mark></div>

                        <button type="submit" class="bg-blue-600">Submit</button>
                    </form>
                </div>
            </div>
        </div>
        <div v-on:click="clearErrors" v-if="Object.keys(this.errors).length > 0" class="fixed top-0 left-0 flex items-center justify-center w-full h-full cursor-pointer">
            <div v-bind:class="{'lg:ml-52 md:ml-40 sm:ml-24': !this.$store.state.isHidden}"
            class="lg:mb-52 md:mb-40 sm:mb-24 bg-white opacity-100 w-1/3 h-1/3 z-50">
                <mark>{{Object.values(errors)[0]}}</mark>
            </div>
            <div class="bg-black opacity-75 w-full h-full absolute top-0 left-0"></div>
        </div>
        <div v-on:click="clearStatus" v-if="$page.flash.updateStatus" class="fixed top-0 left-0 flex items-center justify-center w-full h-full cursor-pointer">
            <div v-bind:class="{'lg:ml-52 md:ml-40 sm:ml-24': !this.$store.state.isHidden}"
            class="lg:mb-52 md:mb-40 sm:mb-24 bg-white opacity-100 w-1/3 h-1/3 z-50">
                {{$page.flash.updateStatus}}
            </div>
            <div class="bg-black opacity-75 w-full h-full absolute top-0 left-0"></div>
        </div>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    export default {
        components: {
            HeaderLayout,
        },
        props: {
            data: Array,
            errors: Object
        },
        methods: {
            submit(id) {
                var data = new FormData(document.getElementById(id))
                this.$inertia.post('/channel/'+id, data)
            },
            clearErrors() {
                this.errors = new Object;
            },
            clearStatus() {
                this.$page.flash.updateStatus = null;
            }
        },
    }
</script>
