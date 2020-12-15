<template>
    <header-layout>
        <div class="py-12">
            <video :src="'../storage/stream/' + data.storedAt" controls> </video>
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
