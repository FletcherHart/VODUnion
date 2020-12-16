<template>
    <header-layout>
        <div class="h-full -mt-4">
            <div class="bg-black w-full flex items-center justify-center h-5/6">
                <video class="h-full" :src="'../storage/stream/' + data.storedAt" controls> </video>
            </div>
            <h2>{{data.title}}</h2>
            <p>{{data.description}}</p>
            <p>Views: {{data.views}}</p>
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
        }
    }
</script>
