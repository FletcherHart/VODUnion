<template>
    <header-layout>
        <div class="sm:flex flex-col sm:items-center m-10 mt-0">
            <div class="sm:w-4/6 flex items-center justify-center flex-col whitespace-pre-wrap">
                <h1 class="font-bold text-2xl">{{changelog.title}}</h1>
                <div>{{changelog.text}}</div>
            </div> 
            <div class="sm:w-4/6 w-full h-40 bg-white rounded mt-5">
                <div v-for="log in past_logs" :key="log.id">
                    <div v-bind:class="{'bg-yellow-200': (log.id == changelog.id)}">
                    <inertia-link :href="'changelog/'+log.id">
                        <div class="relative">
                            <img v-if="(log.id == changelog.id)" class="absolute right-0" width="16px" height="16px" src="/open-iconic/svg/star.svg" alt="current changelog article icon">
                        </div>
                        <div>{{log.title}}</div>
                        <div class="text-gray-600">{{toDate(log.created_at)}}</div>
                    </inertia-link>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import JetInputError from '@/Jetstream/InputError'
    import {toDate, format} from 'date-fns'
    export default {
        components: {
            HeaderLayout,
            JetInputError,
        },
        props: {
            changelog: Object,
            past_logs: Array
        },
        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'POST',
                    title: null,
                    text: null,
                }, {
                    bag: 'changelog',
                    resetOnSuccess: false,
                })
            }
        },
        methods: {
            submit() {
                this.form.post('/admin/changelog', {
                    preserveScroll: true
                });
            },
            toDate(date) {
                return format(toDate(new Date(date)), 'LLLL io, y');
            },
        }
    }
</script>