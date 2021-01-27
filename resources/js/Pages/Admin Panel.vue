<template>
    <header-layout>
        <div class="flex items-center justify-center">
            <h1>Generate Keys</h1>
            <form @submit.prevent="submit">
                <span>
                    <button type="submit" class="bg-blue-600">Submit</button>
                </span>
            </form>
        </div>
        <div class="w-full grid grid-cols-3 gap-4 border-2 border-black" v-for="key in upgradeKeys" :key="key.id">
            <div>
                {{key.upgrade_code}}
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
        props: {
            users: Array
        },
        methods: {
            submit() {
                this.$inertia.post('/admin/ban/{user}');
                //Inertia.reload();
            }
        }
    }
</script>