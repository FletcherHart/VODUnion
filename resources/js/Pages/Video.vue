<template>
    <header-layout>
        <div class="h-auto -mt-4">
            <div class="bg-black w-full flex items-center justify-center h-5/6">
                <video class="h-full w-full" :src="'/stream/' + data.id" controls> </video>
            </div>
            <div class="mt-3 flex justify-center">
                <div class="w-11/12">
                    <div class="flex items-center justify-between">
                        <h2>{{data.title}}</h2>
                        <p>Views: {{data.views}}</p>
                    </div>
                    <hr>
                    <div>
                        <div class="cursor-pointer" @click="toggleDesc">Show All</div>
                        <div v-bind:class="{'truncate': hideDescription, 'h-auto min-h-full': !hideDescription}">{{data.description}}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <comment-layout></comment-layout>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import CommentLayout from '@/Layouts/CommentLayout'
    export default {
        components: {
            HeaderLayout,
            CommentLayout
        },
        props: {
            data: Object,
            comments: Array
        },
        data() {
            return{
                hideDescription: true
            }
        },
        methods: {
            toggleDesc() {
                this.hideDescription = !this.hideDescription
            }
        }
    }
</script>
