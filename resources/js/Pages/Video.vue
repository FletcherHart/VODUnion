<template>
    <header-layout>
        <div class="py-12">
            <h2>{{data.title}}</h2>
            <p>{{data.description}}</p>
            <p>{{data.views}}</p>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="comment in $page.comments" :key="comment.id">
                        <a :href="'user/' + comment.user_id">{{comment.name}}</a>
                        <p>Date Posted {{comment.date}}</p>
                        <p>{{comment.text}}</p>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    export default {
        components: {
            HeaderLayout,
        },
        props: {
            data: Object
        }
    }
</script>
