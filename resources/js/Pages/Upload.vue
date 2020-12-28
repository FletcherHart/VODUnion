<template>
    <header-layout>
        <div class="py-12">
            <div class="flex justify-center mt-16">
                <h2 class="m-auto font-bold">Upload</h2>
            </div>
            <div v-if="progress > 0" class="w-full flex justify-center">
                Progress: {{progress.toFixed(1)}}%
            </div>
            <div class="flex flex-col">
                <div v-if="errors.storage" class="h-full w-full flex justify-center">
                    <mark>{{errors.storage}}</mark>
                </div>
                <div class="h-full w-full flex justify-center">
                    <form @submit.prevent="submit" class="grid grid-cols-1 xl:w-1/3">
                        <span>
                            <label for="video">Video: </label>
                            <input type="file" id="video" name="video" ref="video" accept=".mp4">
                        </span>
                        <div v-if="errors.video"><mark>{{ errors.video }}</mark></div>
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
            errors: Object,
        },
        data() {
            return {
                uid: String,
                urlId: String,
                progress: Number,
                uploadError: String
            }
        },
        methods: {
            submit() {

                // var data = new FormData()
                // data.append('title', title.value || '')
                // data.append('description', description.value || '')
                // data.append('link', video.files[0] || '')
                // data.append('thumbnail', thumb.files[0] || '')
                // data.append('listed', listed.value || '')
                // data.append('raw', raw.value || '')
                // this.$inertia.post('/upload', data)

                var vid = new FormData()
                vid.append('file', video.files[0] || '')

                this.fetchKey().then( data => {
                    const config = {
                        onUploadProgress: progressEvent => this.displayProgress(progressEvent.loaded/progressEvent.total)
                    }

                    uploadVideo(data[1], vid. config).catch(function(error) {
                        if(error.response) {
                            console.log(error.response.data);
                            console.log(error.response.status);
                            console.log(error.response.headers);
                        } else if (error.request) {
                            console.log(error.request);
                        } else {
                            console.log('Error', error.message);
                        }
                    });
                    
                })

            },
            fetchKey() {
                return fetch('key')
                    .then(response =>  response.json())
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            },
            uploadVideo(url, video, config) {
                axios.post(data[1], vid, config);
            },
            displayProgress(percent) {
                console.log(percent*100);
                this.progress = percent*100;
            }

        },
    }
</script>