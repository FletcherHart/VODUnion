<template>
    <header-layout>
        <div class="flex items-center justify-center">
            <h1>Upgrade</h1>
            <form @submit.prevent="submit">
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