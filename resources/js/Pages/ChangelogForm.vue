<template>
    <header-layout>
        <div class="flex items-center justify-center flex-col h-80">
            <form @submit.prevent="submit" class="mt-5 flex flex-col w-full h-full">
                <span class="mb-2 flex flex-col w-4/6">
                    <label for="title">Title:</label>
                    <input name="title" id="title" v-model="form.title">
                </span>
                <jet-input-error v-if="form.errors['title']" :message="form.errors['title']" class="mt-2" />
                
                <label for="text">Content:</label>
                <textarea class="w-4/6 h-4/6 p-1 whitespace-pre-wrap" name="text" id="text" v-model="form.text" aria-multiline="true" multiline/>
                
                <jet-input-error v-if="form.errors['text']" :message="form.errors['text']" class="mt-2" />

                <button type="submit" class="bg-blue-600">Submit</button>
                
            </form>
        </div>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    import JetInputError from '@/Jetstream/InputError'
    export default {
        components: {
            HeaderLayout,
            JetInputError,
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
            }
        }
    }
</script>