<template>
    <header-layout>
        <a :href="route('changelogform')">Changelog Form</a>
        <div class="flex items-center justify-center">
            <h1>List users</h1>
            <button v-on:click="listUsers" type="submit" class="bg-blue-600">Submit</button>
            <h1>List videos</h1>
            <button v-on:click="listVideos" type="submit" class="bg-blue-600">Submit</button>
        </div>
        <div v-if="users" class="w-full border-2 border-black">
            <div class="grid grid-cols-8">
                <div class="col-span-1">Username</div>
                <div class="col-span-2">Email</div>
                <div class="col-span-2">Date Joined</div>
                <div class="col-span-1">Role</div>
                <div class="col-span-1">Status</div>
            </div>
            <div class="grid grid-cols-8" v-for="user in users" :key="user.id">
                <div class="col-span-1 break-words">{{user.name}}</div>
                <div class="col-span-2 break-words">{{user.email}}</div>
                <div class="col-span-2 break-words">{{user.created_at}}</div>
                <div class="col-span-1">{{user.role_id}}</div>
                <div class="col-span-1">{{user.banned}}</div>
                <div class="col-span-1"><button class="bg-red-600 text-white pl-2 pr-2 rounded">Ban User</button></div>
            </div>
        </div>
        <div v-if="videos" class="w-full border-2 border-black">
            <div class="grid grid-cols-9">
                <div class="col-span-1">Title</div>
                <div class="col-span-2">Description</div>
                <div class="col-span-2">Date Uploaded</div>
                <div class="col-span-1">Owner</div>
                <div class="col-span-1">Listed</div>
                <div class="col-span-1">Status</div>
            </div>
            <div class="grid grid-cols-9" v-for="video in videos" :key="video.id">
                <div class="col-span-1 break-words">{{video.title}}</div>
                <div class="col-span-2 break-words">{{video.description}}</div>
                <div class="col-span-2 break-words">{{video.created_at}}</div>
                <div class="col-span-1">{{video.username}}</div>
                <div class="col-span-1">{{video.listed}}</div>
                <div class="col-span-1">{{video.status}}</div>
                <div class="col-span-1"><button class="bg-red-600 text-white pl-2 pr-2 rounded">Delete Video</button></div>
            </div>
        </div>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
import Button from '../Jetstream/Button.vue';
    export default {
        components: {
            HeaderLayout
        },
        props: {
            users: Array,
            videos: Array
        },
        methods: {
            ban() {
                this.$inertia.post('/admin/ban/{user}');
                //Inertia.reload();
            },
            listUsers() {
                this.$inertia.get('/admin/listUsers');
            },
            listVideos() {
                this.$inertia.get('/admin/listVideos');
            }
        }
    }
</script>