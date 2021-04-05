<template>
    <header-layout>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 h-full">
            <div class="w-full flex items-center justify-center mb-5">
                <h1 class="font-bold text-xl">My Videos</h1>
            </div>
            <div v-if="errors.deny">
                <mark>{{errors.deny}}</mark>
            </div>
            <div class="overflow-hidden flex flex-col justify-center mb-10">
                <section v-bind:class="{'hidden': uploadModal}"> 
                    <div class="m-10" >
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
                                    <button type="submit" v-bind:class="{'bg-blue-600 text-white':!progress, 'bg-gray-400 text-black': progress}" :disabled="progress > 0">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </section>
                <div class="flex justify-center">
                    <div class="w-5/6 border-b-2 border-black">
                        <div class="flex justify-between">
                            <div>
                                <span class="flex justify-between">
                                    <span class="flex">
                                        <button v-on:click="reloadPage" class="bg-blue-400 w-10 h-10 flex justify-center items-center rounded" aria-label="refresh videos"><img width="16px" height="16px" src="/open-iconic/svg/loop-circular.svg" alt=""></button>
                                        <button v-on:click="uploadModalDisplay" class="bg-green-400 w-10 h-10 flex justify-center items-center rounded" aria-label="show upload video form">
                                            <img width="16px" height="16px" src="/open-iconic/svg/plus.svg" alt="">
                                        </button>
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span>Date</span>
                                <button v-on:click="sort" class="flex justify-center items-center rounded" aria-label="sort videos by upload date"><img class="w-4 h-full ml-1" id="dateSortImg" src="/open-iconic/svg/arrow-top.svg" alt=""></button>
                            </div>
                        </div>
                    </div>
                </div>
                <article class="w-full mb-5" v-for="video in sortedVideos" :key="video.id">
                    <div class="flex mt-5 p-2 bg-white shadow rounded">
                        <div class="w-full">
                            <div class="flex justify-between">
                                <h2 class="font-semibold" v-if="video.title">{{video.title}}</h2>
                                <h2 class="font-semibold" v-else><i>Untitled</i></h2>
                                <div class="flex">
                                    <div class="flex items-center mr-3">
                                        <img class="mr-2" width="16px" height="16px" src="/open-iconic/svg/eye.svg" alt="">
                                        <p v-if="video.listed">Public</p>
                                        <p v-else>Unlisted</p>
                                    </div>
                                    <div v-if="video.status == 'ready'" class="bg-green-400 rounded p-1 w-min">Ready</div>
                                    <div v-else class="bg-gray-400 rounded p-1 w-min">{{video.status}}</div>
                                </div>
                            </div>
                            <hr>
                            <div class="flex">
                                <div class="mr-5 h-full">
                                    <div class="relative">
                                        <img alt="" :src="'https://videodelivery.net/' +video.videoID+'/thumbnails/thumbnail.jpg?time=0s&height=113&width=200'">
                                        <div v-if="video.video_length > 0" class="absolute bottom-2 text-white bg-black text-sm bg-opacity-50 ml-1">{{getTime(video.video_length)}}</div>
                                    </div>
                                </div>
                                <div class="w-full">
                                    <div class="text-sm">
                                        <div>Views: {{video.views}}</div>
                                        <div>Uploaded: {{setTime(video.created_at)}}</div>
                                    </div>
                                    <div class="flex items-center justify-between w-full">
                                        <button v-on:click="edit(video)" class="w-10 h-10 text-gray-600 flex justify-center items-center" :aria-label="'Edit video with title ' + video.title"><img width="16px" height="16px" src="/open-iconic/svg/pencil.svg" alt=""></button>
                                        <div class="flex flex-row justify-between items-center">
                                            <button v-on:click="deleteVideo(video.id)" class="bg-red-600 rounded p-1 text-white" :aria-label="'Delete video with title ' + video.title">Delete</button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>                    
                </article>
            </div>
        </div>
        <div class="w-full h-full fixed top-0 z-50 flex justify-center bg-black bg-opacity-50"
        v-bind:class="{ 
            'w-full': this.$store.state.isHidden, 
            'lg:-ml-52 md:-ml-40 sm:-ml-24': !this.$store.state.isHidden
        }"
         v-if="editVideo.id">
            <div class="flex flex-col bg-white relative m-auto sm:w-4/6 sm:h-4/5 w-5/6 h-3/5">    
                <div class="flex flex-row-reverse">
                    <button v-on:click="unedit()" class="p-1 mr-1 mt-1" aria-label="stop editing video"> <img width="16px" height="16px" src="/open-iconic/svg/x.svg" alt=""></button>
                </div>
                <form @submit.prevent="submit(editVideo.id)" :id="editVideo.id" class="flex flex-col">
                    <div class="ml-5 mr-5">
                        <label for="title" class="text-gray-400 block relative top-7 ml-3">Title </label>
                        <input class="pl-3 resize-none rounded w-full active:birder-black-900  border-solid border-2 border-black-600 pt-5" id="title" name="title" aria-label="title of video" placeholder="Video Title" :value="editVideo.title">
                    </div>
                    <jet-input-error class="ml-5" v-if="errors.title && formID === editVideo.id" :message="errors.title"/>

                    <div class="ml-5 mr-5">
                        <label for="description" class="text-gray-400 block relative top-7 ml-3">Description</label>
                        <textarea class="pl-3 resize-none rounded w-full active:birder-black-900  border-solid border-2 border-black-600 pt-5" id="description" name="description" aria-label="description of video" rows=5 :value="editVideo.description"/>
                    </div>
                    <jet-input-error class="ml-5" v-if="errors.description && formID === editVideo.id" :message="errors.description"/>

                    <span class="ml-5" v-if="editVideo.status === 'ready'">
                        <label for="list">Make video public:</label>
                        <input v-if="editVideo.listed == 0" type="checkbox" name="list" id="list">
                        <input v-else type="checkbox" name="list" id="list" checked>
                    </span>
                    <div v-if="errors.list"><mark>{{ errors.list }}</mark></div>

                    <button type="submit" class="m-10 bg-blue-600 text-white rounded float-right" aria-label="submit changes to video">Submit</button>
                </form>
                <transition name="fade">
                    <div v-on:click="clearStatus" v-if="$page.props.flash.updateStatus" class="w-full flex items-center justify-center">
                        <div class="fixed bottom-24 bg-gray-900 h-16 w-48 flex items-center justify-center cursor-pointer">
                            <div class="text-center text-white">
                                {{$page.props.flash.updateStatus}}
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>

    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import JetInputError from '@/Jetstream/InputError'
    import { Inertia } from '@inertiajs/inertia'
    import {format,toDate} from 'date-fns'

    export default {
        components: {
            HeaderLayout,
            JetInputError
        },
        props: {
            videos: Array,
            errors: Object,
        },
        data() {
            return {
                uploadModal: Boolean,
                editVideo: Object,
                progress: 0,
                uploadErrors: "",
                videoList: 0,
                formID: 0,
                url: "",
                form: this.$inertia.form({
                    '_method': 'POST',
                    title:null,
                    description:null,
                    list:null
                }),
                sortedVideos: this.videos,
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
            if(this.editVideo.id) {
                this.editVideo = this.videos.find(video => video.id == this.editVideo.id)
            }
        },
        methods: {
            edit(video) {
                this.editVideo = video;
            },
            unedit() {
                this.editVideo = {};
                this.clearErrors();
            },
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
                return format(toDate(new Date(date)), 'LLL d, y');
            }, 
            sort() {
                this.sortedVideos.reverse();
                if(document.getElementById('dateSortImg').classList.contains('upside-down')) {
                    document.getElementById('dateSortImg').classList.remove('upside-down');
                    document.getElementById('dateSortImg').classList.add('upside-up');
                } else {
                    document.getElementById('dateSortImg').classList.remove('upside-up');
                    document.getElementById('dateSortImg').classList.add('upside-down');
                }
            },
            getTime(duration) {
                return new Date(duration * 1000).toISOString().substr(11, 8);
            }
        },
        watch: { 
            videos: function(newVal, oldVal) {
                this.sortedVideos = newVal;
            }
        },
    }
</script>
