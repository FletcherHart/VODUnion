<template>
    <header-layout>
        <article class="sm:flex flex-col sm:items-center m-10 mt-0" v-if="changelog != null && past_logs != null">
            <div class="sm:w-4/6 flex items-center justify-center flex-col whitespace-pre-wrap">
                <h1 class="font-bold text-2xl">{{changelog.title}}</h1>
                <div class="text-gray-600 text-sm">{{toDate(changelog.created_at)}}</div>
                <div class="article-body" v-html="changelog.text"></div>
            </div> 
            <div class="sm:w-4/6 w-full h-40 bg-white rounded mt-5 p-1 overflow-auto">
                <div v-for="log in past_logs" :key="log.id">
                    <div v-bind:class="{'bg-yellow-200': (log.id == changelog.id)}">
                        <inertia-link :href="route('changelog') + '/' + log.id">
                            <div class="relative">
                                <svg v-if="(log.id == changelog.id)" class="absolute right-0" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 8 8">
                                    <path d="M4 0l-1 3h-3l2.5 2-1 3 2.5-2 2.5 2-1-3 2.5-2h-3l-1-3z" />
                                </svg>
                            </div>
                            <div>{{log.title}}</div>
                            <div class="text-gray-700">{{toDate(log.created_at)}}</div>
                        </inertia-link>
                    </div>
                    <hr>
                </div>
            </div>
        </article>
        <div class="flex items-center flex-col mt-0 m-10" v-else>
            <h1 class="font-bold text-2xl">Nothing here right now</h1>
            <div>This page is for documenting changes that occur on the site. Currently, there appears to be no documented changes.</div>
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
                return format(toDate(new Date(date)), 'LLLL do, y');
            },
        }
    }
</script>