<template>
    <header-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="overflow-hidden flex flex-col justify-center">
                <div> 
                    <button v-on:click="uploadModalDisplay">Upload</button>
                    <div v-bind:class="{'hidden': uploadModal}">
                        <div class="flex justify-center">
                            <h2 class="m-auto font-bold">Upload</h2>
                        </div>
                        <div v-if="progress > 0" class="w-full flex justify-center">
                            Progress: {{progress.toFixed(1)}}%
                        </div>
                        <div class="flex flex-col">
                            <div class="h-full w-full flex justify-center">
                                <form @submit.prevent="upload" class="grid grid-cols-1 xl:w-1/3">
                                    <span>
                                        <label for="video">Video: </label>
                                        <input type="file" id="video" name="video" ref="video" accept=".mp4">
                                    </span>
                                    <div v-if="uploadErrors.length > 0"><mark>{{ uploadErrors }}</mark></div>
                                    <button type="submit" v-bind:class="{'bg-blue-600':!progress, 'bg-gray-400': progress}" :disabled="progress > 0">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-full grid grid-cols-3 gap-4 border-2 border-black" v-for="video in data" :key="video.id">
                    <div class="w-40 sm:w-auto">
                        <div class="w-full bg-black flex justify-center ">
                            <img v-if="!video.thumbnail" :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=270'">
                            <img v-else :src="'/storage/thumbnails/' + video.thumbnail">
                        </div>
                    </div>
                    <div>
                        <button v-on:click="deleteVideo(video.id)">Delete</button>
                    </div>
                    <form @submit.prevent="submit(video.id)" class="grid grid-cols-1 col-span-2" :id="video.id">
                        <span>
                            <label for="title">Title: </label>
                            <input class="border-solid border-2 border-black-600" id="title" name="title" placeholder="Video Title" :value="video.title">
                        </span>
                        <div v-if="errors.title && formID === video.id"><mark>{{ errors.title }}</mark></div>

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
        <div v-on:click="clearStatus" v-if="$page.flash.updateStatus" 
        class="fixed bottom-24 left-1/2 bg-gray-900 h-16 w-48 flex items-center justify-center cursor-pointer"
        v-bind:class="{'-ml-24': this.$store.state.isHidden}">
            <div class="text-center text-white">
                {{$page.flash.updateStatus}}
            </div>
        </div>

    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import { Inertia } from '@inertiajs/inertia'
    export default {
        components: {
            HeaderLayout,
        },
        props: {
            data: Array,
            errors: Object
        },
        data() {
            return {
                uploadModal: Boolean,
                url: "",
                progress: 0,
                uploadErrors: "",
                videoList: 0,
                formID: 0,
            }
        },
        methods: {
            submit(id) {
                this.formID = id;
                var data = new FormData(document.getElementById(id));
                this.$inertia.post('/channel/'+id, data);
            },
            clearErrors() {
                this.errors = new Object;
            },
            clearStatus() {
                this.$page.flash.updateStatus = null;
            },
            deleteVideo(id) {
                this.$inertia.delete('/channel/'+id);
            },
            uploadModalDisplay() {
                this.uploadModal = !this.uploadModal;
            },
            upload() {
                this.uploadErrors = "";

                var vid = new FormData()
                vid.append('file', video.files[0] || '')
                const config = {
                    onUploadProgress: progressEvent => this.displayProgress(progressEvent.loaded/progressEvent.total)
                }

                if(this.url.length == 0) {
                    this.fetchKey().then( data => {
                        console.log(data[1]);
                        if(data == null) {
                            this.uploadErrors = "There was an error processing your request. Please try again later.";
                        } else {
                            this.url = data[1];

                            axios.post(this.url, vid, config).then(response => {
                               this.progress = 0;
                               this.uploadModalDisplay();
                               this.$refs.video.value = null;
                               Inertia.reload();
                            }).catch(error => {
                                if(error.response) {
                                    this.progress = 0;
                                    console.log(error.response.data);
                                    console.log(error.response.status);
                                    if(error.response.status == 400) {
                                        this.uploadErrors = "Error: The chosen file is not supported. Please use an mp4 file."
                                    }
                                }
                            });

                        }
                        
                    })
                } else {
                    axios.post(this.url, vid, config).then(x => {
                        this.progress = 0;
                        this.uploadModalDisplay();
                        this.$refs.video.value = null;
                        Inertia.reload();
                    }).catch(error => {
                            if(error.response) {
                                this.uploadErrors = error.message;
                            }
                        });
                }
            },
            fetchKey() {
                return fetch('key')
                    .then(response =>  {
                        if(response.status == 200)  {
                            return response.json();
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            },
            displayProgress(percent) {
                this.progress = percent*100;
            }
        },
    }
</script>
