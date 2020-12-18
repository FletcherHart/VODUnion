<template>
    <header-layout>
        <div class="py-12">
            <div class="flex justify-center mt-16">
                <h2 class="m-auto font-bold">Upload</h2>
            </div>
            <div class="flex flex-col">
                <div v-if="errors.storage" class="h-full w-full flex justify-center">
                    <mark>{{errors.storage}}</mark>
                </div>
                <div class="h-full w-full flex justify-center">
                    <form @submit.prevent="submit" class="grid grid-cols-1 w-1/3">
                        <input class="border-solid border-2 border-black-600" id="title" name="title" placeholder="Video Title" >
                        <div v-if="errors.title"><mark>{{ errors.title }}</mark></div>
                        <input class="border-solid border-2 border-black-600" id="description" name="description" rows=5 placeholder="Description">
                        <div v-if="errors.description"><mark>{{ errors.description }}</mark></div>
                        <input type="file" id="video" name="video" ref="video" accept=".mp4">
                        <div v-if="errors.video"><mark>{{ errors.video }}</mark></div>
                        <span>
                            <label for="listed">List video immediatly upon upload?</label>
                            <input type="checkbox" name="listed" id="listed">
                        </span>
                        <div v-if="errors.listed"><mark>{{ errors.listed }}</mark></div>
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
            errors: Object
        },
        methods: {
            submit() {
                var data = new FormData()
                data.append('title', title.value || '')
                data.append('description', description.value || '')
                data.append('video', video.files[0] || '')
                data.append('listed', listed.value || '')

                this.$inertia.post('/upload', data)
            }
        },
    }
</script>