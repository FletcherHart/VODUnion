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
                <div class="h-full w-full flex justify-center">
                    <form @submit.prevent="submit" class="grid grid-cols-1 xl:w-1/3">
                        <span>
                            <label for="video">Video: </label>
                            <input type="file" id="video" name="video" ref="video" accept=".mp4">
                        </span>
                        <div v-if="errors.length > 0"><mark>{{ errors }}</mark></div>
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
        },
        data() {
            return {
                url: "",
                progress: Number,
                errors: "",
            }
        },
        methods: {
            submit() {
                this.errors = "";
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
                const config = {
                    onUploadProgress: progressEvent => this.displayProgress(progressEvent.loaded/progressEvent.total)
                }

                if(this.url.length == 0) {
                    this.fetchKey().then( data => {
                        console.log(data[1]);
                        if(data == null) {
                            this.errors = "There was an error processing your request. Please try again later.";
                        } else {
                            this.url = data[1];

                            axios.post(this.url, vid, config).catch(error => {
                                if(error.response) {
                                    this.progress = 0;
                                    console.log(error.response.data);
                                    console.log(error.response.status);
                                    if(error.response.status == 400) {
                                        this.errors = "Error: The chosen file is not supported. Please use an mp4 file."
                                    }
                                }
                            });

                        }
                        
                        // .then( data => {
                        //     this.displayError(data);
                        //     console.log(this.errors.video);
                        // });
                        
                    })
                } else {
                    axios.post(this.url, vid, config).catch(error => {
                            if(error.response) {
                                this.errors = error.message;
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
                //console.log(percent*100);
                this.progress = percent*100;
            },
        },
    }
</script>