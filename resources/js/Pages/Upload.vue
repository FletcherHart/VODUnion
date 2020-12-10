<template>
    <header-layout>
        <div class="py-12">
            <div class="flex justify-center">
                <h2 class="m-auto font-bold">Upload</h2>
            </div>
            <div class="flex justify-center">
                <form @submit.prevent="submit" class="grid grid-cols-1 w-1/3">
                    <input class="border-solid border-2 border-black-600" id="title" name="title" placeholder="Video Title" >
                    <input class="border-solid border-2 border-black-600" id="description" name="description" rows=5 placeholder="Description">
                    <input type="file" id="video" name="video" ref="video" accept=".mp4">
                    <span>
                        <label for="listed">List video immediatly upon upload?</label>
                        <input type="checkbox" name="listed" id="listed">
                    </span>
                    <button type="submit" class="bg-blue-600">Submit</button>
                </form>
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