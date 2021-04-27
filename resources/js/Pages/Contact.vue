<template>
    <header-layout>
        <div v-if="!done" class="flex items-center justify-center flex-col">
            <h1 class="font-bold text-2xl">Contact Us</h1>
            <form @submit.prevent="submit" class="mt-5 mb-10 w-1/2">
                <div class="flex flex-col">
                    <label for="subject">Subject</label>
                    <jet-input-error v-if="form.errors.subject" :message="form.errors['subject']" class="mt-2" />
                    <input class="shadow-inner border border-gray-400 p-1" name="subject" id="subject" v-model="form.subject" required>

                    <label for="email">Email</label>
                    <jet-input-error v-if="form.errors.email" :message="form.errors['email']" class="mt-2" />
                    <input class="shadow-inner border border-gray-400 p-1" name="email" id="email" v-model="form.email" required>

                    <label for="message">Message</label>
                    <jet-input-error v-if="form.errors.message" :message="form.errors['message']" class="mt-2" />
                    <textarea class="shadow-inner border border-gray-400 p-1" rows=8 name="message" id="message" v-model="form.message" required/>

                    <button type="submit" class="bg-blue-600 mt-5 text-white">Submit</button>
                </div>
            </form>
        </div>
        <div v-else class="flex items-center justify-center flex-col">
            <h1 class="font-bold text-2xl">Your message has been sent</h1>
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
        props: {
            done: Boolean
        },
        data() {
            return {
                form: this.$inertia.form({
                    '_method': 'POST',
                    subject: null,
                    email: null,
                    message: null
                })
            }
        },
        methods: {
            submit() {
                this.form.post(route('contact'), {
                    preserveScroll: true
                });
            }
        }
    }
</script>