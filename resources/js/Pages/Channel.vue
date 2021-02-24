<template>
    <header-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div v-if="errors.deny">
                <mark>{{errors.deny}}</mark>
            </div>
            <div class="overflow-hidden flex flex-col justify-center">
                <div> 
                    <span class="flex justify-between">
                        <button v-on:click="reloadPage" class="bg-blue-400 w-10 h-10 flex justify-center items-center rounded"><img class="icon" src="/open-iconic/svg/loop-circular.svg" alt="refresh videos icon"></button>
                        <button v-on:click="uploadModalDisplay" class="bg-green-400 w-10 h-10 flex justify-center items-center rounded">
                            <img class="icon" src="/open-iconic/svg/plus.svg" alt="add video icon">
                        </button>
                    </span>
                    <div class="m-10" v-bind:class="{'hidden': uploadModal}">
                        <div class="flex justify-center">
                            <h2 class="m-auto font-bold">Upload</h2>
                        </div>
                        <div v-if="progress > 0" class="w-full flex justify-center">
                            Progress: {{progress.toFixed(1)}}%
                        </div>
                        <div class="flex flex-col">
                            <div class="h-full w-full flex justify-center">
                                <form @submit.prevent="fetchKey" class="grid grid-cols-1 xl:w-1/3">
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
                <div class="w-full grid grid-cols-3 gap-4 border-2 border-black" v-for="video in videos" :key="video.id">
                    <div class="w-40 sm:w-auto">
                        <div class="w-full bg-black flex justify-center ">
                            <img v-if="!video.thumbnail" :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=270'">
                            <img v-else :src="'/storage/thumbnails/' + video.thumbnail">
                        </div>
                        <div>Date Uploaded: {{setTime(video.created_at)}}</div>
                    </div>
                    <div class="col-span-2">
                        <div class="flex flex-row justify-between">
                            <div v-if="video.status == 'done'" class="bg-green-400 rounded p-1 w-min">Ready</div>
                            <div v-else-if="video.status == 'processing'" class="bg-gray-400 rounded p-1 w-min">Ready</div>
                            <button v-on:click="deleteVideo(video.id)" class="bg-red-600 rounded p-1 text-white">Delete</button>
                        </div>
                        <form @submit.prevent="submit(video.id)" class="grid grid-cols-1" :id="video.id">
                            <span>
                                <label for="title">Title: </label>
                                <input class="border-solid border-2 border-black-600" id="title" name="title" placeholder="Video Title" :value="video.title">
                            </span>
                            <jet-input-error v-if="errors.title && formID === video.id" :message="errors.title"/>

                            <span class="w-full">
                                <label for="description">Description:</label>
                                <textarea class="border-solid border-2 border-black-600 resize-none" id="description" name="description" rows=5 placeholder="Description" :value="video.description"/>
                            </span>
                            <jet-input-error v-if="errors.description && formID === video.id" :message="errors.description"/>

                            <!-- <span>
                                <label for="thumbnail">Thumbnail: </label>
                                <input type="file" id="thumbnail" name="thumbnail" ref="thumbnail" accept=".jpeg, .jpg, .png">
                            </span> -->
                            <!-- <div v-if="errors.thumb"><mark>{{ errors.thumb }}</mark></div> -->

                            <span v-if="video.status === 'done'">
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
        </div>
        <transition name="fade">
            <div v-on:click="clearStatus" v-if="$page.props.flash.updateStatus" 
            class="fixed bottom-24 left-1/2 bg-gray-900 h-16 w-48 flex items-center justify-center cursor-pointer"
            v-bind:class="{'-ml-24': this.$store.state.isHidden}">
                <div class="text-center text-white">
                    {{$page.props.flash.updateStatus}}
                </div>
            </div>
        </transition>

    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import JetInputError from '@/Jetstream/InputError'
    import { Inertia } from '@inertiajs/inertia'
    import moment from 'moment'
    export default {
        components: {
            HeaderLayout,
            JetInputError
        },
        props: {
            videos: Array,
            errors: Object
        },
        data() {
            return {
                uploadModal: Boolean,
                progress: 0,
                uploadErrors: "",
                videoList: 0,
                formID: 0,
                url: "",
                form: this.$inertia.form({
                    '_method': 'POST',
                    text:null
                }, {
                        bag: 'video',
                        resetOnSuccess: false,
                    }
                ),
            }
        },
        updated: function() {
            this.$nextTick(function() {
                if(this.$page.props.flash.updateStatus) {
                    setTimeout(() => {  this.clearStatus(); }, 3000);
                }
            });
            if(this.$page.props.flash.url) {
                this.url = this.$page.props.flash.url;
                this.$page.props.flash.url = null;
                this.upload();
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
                this.$page.props.flash.updateStatus = null;
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
            },
            fetchKey() {
                this.$inertia.post('key', { preserveState: true });
            },
            displayProgress(percent) {
                this.progress = percent*100;
            },
            reloadPage() {
                Inertia.reload();
            },
            setTime(date) {
                moment.locale();
                return moment(date).format('MMMM Do YYYY, hh:mm a');
            }
        },
    }
</script>
