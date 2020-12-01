<template>
    <header-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Video
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div>
                        {{$page.data}}
                        <h2>{{$page.data.title}}</h2>
                        <p>{{$page.data.description}}</p>
                    </div>
                    <hr>
                    <div v-for="comment in $page.comments" :key="comment.id">
                        <p>{{comment.name}}</p>
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
            HeaderLayout
        },
    }
</script>
