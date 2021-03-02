<template>
    <header-layout>
        <div class="flex items-center justify-center flex-col">
            <div class="font-bold">Upgrade</div>
            <div class="w-1/3 break-words">Video uploads are restricted to verified users at this time. Users with a code may enter it below, which will upgrade your account and
                allow you to upload up to <b>3</b> videos.
            </div>
            <form @submit.prevent="submit" class="mt-5">
                <span>
                    <label for="code">Code:</label>
                    <input name="code" id="code" v-model="form.code">
                    <button type="submit" class="bg-blue-600">Submit</button>
                </span>
            </form>
        </div>
    </header-layout>
</template>

<script>
    import HeaderLayout from '@/Layouts/HeaderLayout'
    export default {
        components: {
            HeaderLayout
        },
        data() {
            return {
                form: {
                    code: null,
                },
            }
        },
        methods: {
            submit() {
                this.$inertia.post('/upgrade', this.form)
            }
        }
    }
</script>